<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Admin extends CI_Controller
{
	private $event_id = 0;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('Grocery_CRUD');
		$this->load->library('encrypt');
		$this->load->model('AllModel');
		$this->load->library('session');

		# session
		$session_data = $this->session->all_userdata();

		if (!isset($session_data['logged'])) {
			header('Location: ' . base_url('auth/login'));
			die();
		}
	}

	function _crud_output($output = null, $data = null)
	{
		$output = (array)$output;
		$output['title'] = $data['title'];
		$this->load->view('CrudView', $output);
	}

	/*
	 * quản lý submition
	 */

	function index()
	{
		try {
			$crud = new grocery_CRUD();
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('submission')
				->set_subject('Submission')
				->unset_export()
				->unset_add()
				->unset_edit()
				->unset_print();

			$crud->display_as('id', 'ID')
				->display_as('flag', 'Flag')
				->display_as('correct', 'Correct')
				->display_as('challenge_id', 'Thử thách')
				->display_as('user_id', 'Người dùng')
				->display_as('ip_address', 'IP Address')
				->display_as('user_agent', 'User-Agent')
				->display_as('created_at', 'Thời gian');
			$crud->set_relation('user_id', 'user', 'username')
				->set_relation('challenge_id', 'challenge', 'name');
			$crud->columns('created_at', 'user_id', 'ip_address', 'challenge_id', 'flag', 'correct');


			// $crud->order_by('id', 'desc');

			$crud->callback_column('created_at', array($this, '_callback_time'));
			$crud->field_type('correct', 'dropdown', array('0' => 'No', '1' => 'Yes'));

			$output = $crud->render();
			$data['title'] = "Submission";
			$this->_crud_output($output, $data);
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	function logs()
	{
		try {
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('logs')
				->set_subject('Logs')
				->unset_export()
				->unset_add()
				->unset_edit()
				->unset_print();
			$crud->display_as('id', 'ID')
				->display_as('ip_address', 'IP Address')
				->display_as('user_agent', 'User Agent')
				->display_as('created_at', 'Time');
			$crud->columns('id', 'user_id', 'text', 'ip_address', 'user_agent', 'created_at');
			$crud->callback_column('created_at', array($this, '_callback_time'));

			$crud->order_by('id', 'desc');
			$output = $crud->render();
			$data['title'] = "Logs";
			$this->_crud_output($output, $data);
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

#################################################################################
	/*
	 * Quản lý đơn vị
	 */

	function organization()
	{
		try {
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('organization')
				->set_subject('Trường')
				->unset_export()
				->unset_print();

			$crud->display_as('name', 'Đơn vị');
			$crud->required_fields('name', 'location');
			$crud->field_type('location', 'dropdown',
				array('HN' => 'Miền Bắc', 'DN' => 'Miền Trung', 'HCM' => 'Miền Nam'));
			$crud->order_by('id', 'desc');
			$crud->columns('id','name', 'location');

			$output = $crud->render();
			$data['title'] = "Trường";
			$this->_crud_output($output, $data);
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

################################################################################

	/*
	 * Quản lý người dùng
	 */

	function user()
	{
		try {
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('user')
				->set_subject('Tài khoản')
				->unset_print()
				->unset_read();
			$crud->columns('id', 'username', 'active', 'organization_id', 'score', 'last_submit');
			$crud->display_as('id', 'ID')
				->display_as('username', 'Tài khoản')
				->display_as('score', 'Điểm')
				->display_as('organization_id', 'Trường')
				->display_as('active', 'Active')
				->display_as('last_submit', 'Last submit');

			$crud->required_fields('username', 'active')
				->unique_fields('username');
			$crud->set_relation('organization_id', 'organization', 'name');
			$crud->fields('username', 'password', 'organization_id', 'active');

			$action = $crud->getState();
			if ($action == 'add') {
				$crud->required_fields('username', 'active', 'password', 'organization_id');
			}
			$crud->callback_edit_field('password', array($this, 'user_password_callback'));
			$crud->set_rules('password', 'Mật khẩu', "callback_user_password_check[" . $action . "]");

			$crud->order_by('id', 'desc');
			$crud->callback_column('last_submit', array($this, '_callback_time'));
			$crud->callback_insert(array($this, 'user_insert_callback'));
			$crud->callback_update(array($this, 'user_update_callback'));
			$output = $crud->render();
			$data['title'] = "Người dùng";
			$this->_crud_output($output, $data);
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	function user_password_callback()
	{
		return '<input type="text" name="password" value="" autocomplete="off">';
	}

	//Check Password
	public function user_password_check($user_password, $action)
	{
		if ($action == 'insert_validation' && $user_password == '') {
			$this->form_validation->set_message('user_password_check', "Mật khẩu không được rỗng");
			return false;
		}
		return true;
	}

	//callback update and insert
	function user_insert_callback($post_array)
	{
		$post_array['password'] = password_hash($post_array['password'], PASSWORD_BCRYPT);
		if ($this->AllModel->insert('user', $post_array)) {
			return true;
		}
		return false;
	}

	function user_update_callback($post_array, $primary_key)
	{
		if($post_array['password']){
			$post_array['password'] = password_hash($post_array['password'], PASSWORD_BCRYPT);
		}else{
			unset($post_array['password']);
		}
		if($this->AllModel->update('user', $post_array, ['id'=>$primary_key])){
			return true;
		}
		return false;
	}


################################################################################
	/*
	 * Quản lý chủ đề
	 */

	function category()
	{
		try {
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('category')
				->set_subject('Chủ đề')
				->unset_export()
				->unset_print();

			$crud->display_as('name', 'Chủ đề')->display_as('tag', 'Tag')
				->display_as('description', 'Mô tả');
			$crud->required_fields('name');
			$output = $crud->render();
			$data['title'] = "Chủ đề";
			$this->_crud_output($output, $data);
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

################################################################################

	function challenge()
	{
		try {
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('challenge')
				->set_subject('Thử thách')
				->unset_export()
				->unset_print();
			$crud->display_as('id', 'ID')
				->display_as('name', 'Thử thách')
				->display_as('description', 'Đề bài')
				->display_as('flag', 'Flag')
				->display_as('score', 'Điểm')
				->display_as('category_id', 'Chủ đề')
				->display_as('active', 'Active');
			$crud->columns('id', 'category_id', 'name', 'description', 'flag', 'score', 'active');
			$crud->order_by('id', 'desc');
			$crud->required_fields('name', 'description', 'flag', 'score', 'category_id', 'active');

			$crud->add_fields('name', 'description', 'flag', 'score', 'category_id');

			$crud->set_relation('category_id', 'category', 'name');
			$crud->unset_texteditor('flag');

			//Nếu update thì insert notification
			$crud->callback_before_insert(array($this, 'challenge_before_insert'));
			// $crud->callback_update(array($this, 'challenge_callback_update'));
			$crud->callback_before_update(array($this, 'challenge_before_update'));

			$crud->callback_column('flag', array($this, '_callback_decrypt_flag'));
			$crud->callback_edit_field('flag', array($this, '_callback_edit_decrypt_flag'));
			$output = $crud->render();
			$data['title'] = "Thử thách";
			$this->_crud_output($output, $data);
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	function challenge_before_update($post_array, $primary_key)
	{
		$challenge = $this->AllModel->select_one('challenge', array('id' => $primary_key));
		if (!$challenge->active and $post_array['active']) {
			$category = $this->AllModel->select_one('category', array('id' => $challenge->category_id));
			$category_tag = $category->tag ? $category->tag : $category->name;
			$notification_text = "Thử thách {$challenge->name} ($category_tag-$challenge->score) vừa được mở";
			$d = array(
				'type' => CHALLENGE_OPEN,
				'text' => $notification_text,
			);
			$this->AllModel->insert('notification', $d);
		}
		$post_array['flag'] = $this->encrypt->encode($post_array['flag'], FLAG_SECRET_KEY);
		$this->AllModel->update('challenge', $post_array, array('id' => $primary_key));

		return $post_array;
	}

	//callback update and insert
	function challenge_before_insert($post_array)
	{
		error_reporting(0);
		$post_array['flag'] = $this->encrypt->encode($post_array['flag'], FLAG_SECRET_KEY);
		return $post_array;
	}

	function _callback_decrypt_flag($value, $row)
	{
		error_reporting(0);
		return $this->encrypt->decode($value, FLAG_SECRET_KEY);
	}

	function _callback_edit_decrypt_flag($value, $primary_key)
	{
		error_reporting(0);
		$value = $this->encrypt->decode($value, FLAG_SECRET_KEY);
		return '<input type="text" name="flag" value="' . $value . '"/>';
	}
	function test(){
		error_reporting(0);
		$this->load->library('encrypt');
		echo $this->encrypt->encode('testaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '1234');
	}

################################################################################
	/*
	 * quản lý tin tức
	 */

	function notify()
	{
		try {
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('notification')
				->set_subject('Thông báo')
				->unset_export()
				->unset_print()
				->unset_texteditor();

			$crud->display_as('type', 'Kiểu tin tức')
				->display_as('text', 'Tin tức')
				->display_as('updated_at', 'Thời gian');
			$crud->required_fields('text', 'type');
			$crud->unset_texteditor('text');
			
			$crud->callback_column('updated_at', array($this, '_callback_time'));
			$crud->fields('text', 'type');
			$crud->field_type('type', 'dropdown',
				array(NEWS => 'Tin tức', CHALLENGE_HINT => 'Gợi ý mới', CHALLENGE_OPEN => 'Mở thử thách', CHALLENGE_SOLVED => 'Giải bài'));

			$crud->order_by('updated_at', 'desc');

			$output = $crud->render();
			$data['title'] = "Thông báo";
			$this->_crud_output($output, $data);
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}


################################################################################

	function hint()
	{
		try {
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('hint')
				->set_subject('Gợi ý')
				->unset_export()
				->unset_print();

			$crud->display_as('challenge_id', 'Thử thách')
				->display_as('text', 'Gợi ý')
				->display_as('active', 'Active');
			$crud->unset_texteditor('text');
			$crud->order_by('id', 'desc');
			$crud->required_fields('challenge_id', 'text', 'active');
			$crud->columns('id', 'challenge_id', 'text', 'active');
			$crud->set_relation('challenge_id', 'challenge', 'name');
			$crud->fields('challenge_id', 'text', 'active');
			#$crud->edit_fields('challenge_id', 'text', 'hint_active');

			// $crud->callback_update(array($this, 'hint_update'));
			$crud->callback_before_update(array($this, 'hint_before_update'));
			$crud->callback_after_insert(array($this, 'hint_after_insert'));
			$output = $crud->render();
			$data['title'] = "Chủ đề";
			$this->_crud_output($output, $data);
		} catch (Exception $e) {
			show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
		}
	}

	function hint_before_update($post_array, $primary_key){
		$hint = $this->AllModel->select_one('hint', array('id'=>$primary_key));
		if(!$hint->active and $post_array['active']){
			$challenge = $this->AllModel->select_one('challenge', array('id' => $post_array['challenge_id']));
			if($challenge){
				$category = $this->AllModel->select_one('category', array('id' => $challenge->category_id));
				$category_tag = $category->tag ? $category->tag : $category->name;
				$notification_text = "Thử thách {$challenge->name} ($category_tag-$challenge->score) có gợi ý mới";
				$d = [
					'type'=> CHALLENGE_HINT,
					'text'=> $notification_text
				];
				$this->AllModel->insert('notification', $d);
			}
		}
		return $post_array;
	}
	function hint_after_insert($post_array, $primary_key){
		if($post_array['active']){
			$challenge = $this->AllModel->select_one('challenge', array('id' => $post_array['challenge_id']));
			if($challenge){
				$category = $this->AllModel->select_one('category', array('id' => $challenge->category_id));
				$category_tag = $category->tag ? $category->tag : $category->name;
				$notification_text = "Thử thách {$challenge->name} ($category_tag-$challenge->score) có gợi ý mới";
				$d = [
					'type'=> CHALLENGE_HINT,
					'text'=> $notification_text
				];
				$this->AllModel->insert('notification', $d);
			}
		}
		return true;
	}


################################################################################

	function _callback_time($value)
	{
		return date('H:i:s d-m-Y', strtotime($value));
	}
}
