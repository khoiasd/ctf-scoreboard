<?php

/**
 * Created by PhpStorm.
 * User: khoidv1
 * Date: 10/31/2017
 * Time: 3:15 PM
 */
class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('Response_helper');
        $this->load->helper('Auth_helper');
        $this->load->model('UserModel');
    }

    function notify()
    {
        $limit = $this->input->get('limit');
        # notify admin
        $where_admin = [
            'table' => 'notification',
            'order_by' => array('updated_at' => 'desc'),
            'where' => array('type !=' => CHALLENGE_SOLVED)
        ];
        $where_user = [
            'table' => 'notification',
            'order_by' => array('updated_at' => 'desc'),
            'where' => array('type' => CHALLENGE_SOLVED)
        ];

        if ($limit) {
            $where_admin['limit'] = $limit;
            $where_user['limit'] = $limit;
        }
        $notify_user = $this->AllModel->select_all($where_user);
        $notify_admin = $this->AllModel->select_all($where_admin);

        $result = [
            'admin' => [],
            'user' => []
        ];

        foreach ($notify_user as $notify) {
            $notify->updated_at = date('h:i:s a', strtotime($notify->updated_at));
            $result['user'][] = $notify;
        }
        foreach ($notify_admin as $notify) {
            $notify->updated_at = date('h:i:s a', strtotime($notify->updated_at));
            $result['admin'][] = $notify;
        }
        response_success($result);
        return;
    }

    function sign_out()
    {
        if (Auth_logged()) {
            # insert table log login #
            $ip_address = $this->input->ip_address();
            $user_agent = $this->input->user_agent();
            $data['ip_address'] = $ip_address;
            $username = Auth_username();
            $data['text'] = "{$username} - Đăng xuất thành công!";
            $data['user_agent'] = $user_agent;
            $this->AllModel->insert('logs', $data);
            $this->session->sess_destroy();
            response_success('Đăng xuất thành công.');
        } else {
            response_error('Bạn chưa đăng nhập.');
        }
        return;
    }

    function sign_in()
    {
        # đã login
        if (Auth_logged()) {
            response_error('Bạn đã đăng nhập');
            return;
        }
        $this->form_validation->set_rules('username', 'Tài khoản', VALIDATE_RULE . '|min_length[2]|max_length[30]');
        $this->form_validation->set_rules('password', 'Mật khẩu', VALIDATE_RULE . '|min_length[6]');
        if ($this->form_validation->run()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user = $this->UserModel->signin($username, $password);
            if (!$user) {
                $resp_data = 'Tài khoản hoặc mật khẩu không chính xác';
                response_error($resp_data);
            } else {
                $session_login = array(
                    'username' => $user->username,
                    'id' => $user->id
                );
                $this->session->set_userdata('user', $session_login);
                # insert table log login #
                $resp_data = 'Đăng nhập thành công';
                response_success($resp_data);
            }
            $ip_address = $this->input->ip_address();
            $user_agent = $this->input->user_agent();
            $data['ip_address'] = $ip_address;
            $data['text'] = "{$this->input->post('username')} - {$resp_data}";
            $data['user_agent'] = $user_agent;
            $this->AllModel->insert('logs', $data);
        } else {
            response_error(strip_tags(validation_errors()));
        }
        return;
    }

    function user_detail()
    {
        if (!Auth_logged()) {
            response_error('Bạn không có quyền thực hiện chức năng này', 403);
            return;
        }
        $user_id = Auth_id();
        $user = $this->UserModel->user_detail($user_id);
        $data = array(
            'id' => $user->id,
            'username' => $user->username,
            'organization' => $user->organization,
        );
        response_success($data);
        return;
    }

    function user_password()
    {
        if (!Auth_logged()) {
            response_error('Bạn không có quyền thực hiện chức năng này', 403);
            return;
        }
        $user_id = Auth_id();
        $username = Auth_username();
        $this->form_validation->set_rules('password', 'Mật khẩu', VALIDATE_RULE . '|min_length[6]');
        $this->form_validation->set_rules('newpassword', 'Mật khẩu mới', VALIDATE_RULE . '|min_length[6]');
        $this->form_validation->set_rules('repassword', 'Mật khẩu mới', 'matches[newpassword]');
        if ($this->form_validation->run()) {
            $password = $this->input->post('password');
            $user = $this->UserModel->signin($username, $password);
            if (!$user) {
                response_error('Mật khẩu không chính xác');
                return;
            }
            $new_password = $this->input->post('newpassword');
            $update = $this->UserModel->update(array('password' => password_hash($new_password, PASSWORD_BCRYPT)), $user_id);
            if (!$update) {
                response_error('Đổi mật khẩu không thành công');
                return;
            }
            response_success('Đổi mật khẩu thành công');
            return;
        }
        response_error(strip_tags(validation_errors()));
        return;
    }

    function challenge_list()
    {
        if (!Auth_logged()) {
            response_error('Bạn không có quyền thực hiện chức năng này', 403);
            return;
        }
        $user_id = Auth_id();
        $user = $this->AllModel->select_one('user', array('id' => $user_id));
        $categories = $this->AllModel->select_all(array('table' => 'category', 'select' => 'id, name'));
        foreach ($categories as $category) {
            $w = array(
                'category_id' => $category->id
            );
            $challenges = $this->AllModel->select_all(array('table' => 'challenge', 'select' => 'id, name, score, active', 'where' => $w, 'order_by' => array('score' => 'asc')));
            foreach ($challenges as $challenge) {
                $solved = $this->AllModel->count_solved($challenge->id, $user_id);
                $count_solved = $this->AllModel->count_solved($challenge->id);
                $challenge->solved = $solved;
                $challenge->count_solved = $count_solved;
            }
            $category->challenges = $challenges;
        }
        $playing = Auth_playing();
        if($playing){
            $countdown =strtotime(STOPPED_AT) * 1000;
        }else{
            $countdown = strtotime(STARTED_AT) * 1000;
        }
        response_success(['categories' => $categories, 'playing' => $playing, 'countdown' => $countdown, 'score' => $user->score]);
        return;
    }

    function challenge_detail($id = null)
    {
        if (!Auth_logged() or !Auth_playing()) {
            response_error('Bạn không có quyền thực hiện chức năng này', 403);
            return;
        }
        $user_id = Auth_id();
        $challenge = $this->AllModel->select_one('challenge', array('id' => $id, 'active' => 1), 'id, name, description, flag, score, active');
        if ($challenge) {
            $solved = $this->AllModel->count_solved($challenge->id, $user_id);
            $count_solved = $this->AllModel->count_solved($challenge->id);
            $hints = $this->AllModel->select_all(array('table' => 'hint', 'select' => 'id, text', 'where' => array('active' => 1, 'challenge_id' => $challenge->id)));
            $challenge->hints = $hints;
            $challenge->solved = $solved;
            $challenge->count_solved = $count_solved;
            response_success($challenge);
            return;
        } else {
            response_error('Bạn không có quyền thực hiện chức năng này', 403);
            return;
        }
    }

    function challenge_submit()
    {
        if (!Auth_logged() or !Auth_playing()) {
            response_error('Bạn không có quyền thực hiện chức năng này', 403);
            return;
        }
        $user_id = Auth_id();

        $this->form_validation->set_rules('id', 'ID challenge', VALIDATE_RULE . '|numeric');
        $this->form_validation->set_rules('flag', 'Flag', VALIDATE_RULE);
        if ($this->form_validation->run()) {
            $id = $this->input->post('id');
            $flag = $this->input->post('flag');
            $challenge = $this->AllModel->select_one('challenge', array('id' => $id, 'active' => 1));
            if (!$challenge) {
                response_error('Bạn không có quyền thực hiện chức năng này', 403);
                return false;
            }
            # Brute force
            $session_data = $this->session->userdata('user');
            if (isset($session_data['last_submit'])) {
                if (time() - $session_data['last_submit'] <= 5) {
                    response_error('Bạn phải chờ tối thiểu 5s để trả lời tiếp');
                    return;
                }
            }
            $session_data['last_submit'] = time();
            $this->session->set_userdata('user', $session_data);
            $solved = $this->AllModel->count_solved($id, $user_id);
            if ($solved) {
                response_error('Thử thách này đã được giải.');
                return;
            }
            $submit = $this->do_submit($challenge, $flag);
            if (!$submit) {
                response_error('Flag chưa chính xác, vui lòng thử lại.');
                return;
            }
            response_success("Flag chính xác, bạn được cộng {$challenge->score} điểm");
            return;
        }
        response_error(strip_tags(validation_errors()));
        return;
    }

    private function check_flag($flag, $db_flag)
    {
        error_reporting(0);
        $this->load->library('encrypt');
        return $this->encrypt->decode($db_flag, FLAG_SECRET_KEY) === $flag;
    }

    private function do_submit($challenge, $flag)
    {
        error_reporting(0);
        $user_id = Auth_id();
        $username = Auth_username();
        $is_correct = $this->check_flag($flag, $challenge->flag);
        //log submit
        $submission = array(
            'flag' => $flag,
            'correct' => $is_correct,
            'challenge_id' => $challenge->id,
            'user_id' => $user_id,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent()
        );
        $this->AllModel->insert('submission', $submission);

        if ($is_correct) {
            $category = $this->AllModel->select_one('category', array('id' => $challenge->category_id));
            $category_tag = $category->tag ? $category->tag : $category->name;
            $submit = array(
                'user_id' => $user_id,
                'challenge_id' => $challenge->id
            );
            # duplicate entry => đã trả lời rồi
            if (!$this->db->insert('submit', $submit)) {
                return false;
            }
            $score = $challenge->score;
            $this->AllModel->increment_score($user_id, $score);
            $notification_text = "{$username} vừa giải xong thử thách {$challenge->name} ({$category_tag}-{$score})";
            $notification = array(
                'type' => CHALLENGE_SOLVED,
                'text' => $notification_text,
            );
            $this->AllModel->insert('notification', $notification);
            return true;
        }
        return false;
    }

    function scoreboard()
    {
        $location = $this->input->get('l');
        if (!in_array($location, ['HN', 'DN', 'HCM'])) {
            $location = null;
        }
        $user_list = $this->UserModel->user_list($location);
        $challenge_lst = $this->AllModel->select_all(
            array(
                'select' => 'challenge.*, category.tag',
                'table' => 'challenge',
                'join' => array('category' => 'challenge.category_id = category.id'),
                'where' => array('challenge.active' => 1),
                'order_by' => array('category.id' => 'asc', 'challenge.score' => 'asc')
            ));
        foreach ($user_list as $user) {
            $get_solved = $this->AllModel->select_all(['select' => 'challenge_id', 'table' => 'submit', 'where' => array('user_id' => $user->id)]);
            $solved_lst = array_column($get_solved, 'challenge_id');
            $user_solved = [];
            $category_id = null;
            foreach ($challenge_lst as $challenge) {
                $c = [
                    'name' => "$challenge->name - ($challenge->tag $challenge->score)",
                    'solved' => in_array($challenge->id, $solved_lst),
                ];
                if ($category_id !== null and $category_id !== $challenge->category_id) {
                    $space = [];
                    $user_solved[] = $space;
                }
                $user_solved[] = $c;
                $category_id = $challenge->category_id;
            }
            if ($user->last_submit) {
                $user->last_submit = date('h:i:s a', strtotime($user->last_submit));
            }
            $user->challenge = $user_solved;
        }
        response_success($user_list);
        return;
    }
}
