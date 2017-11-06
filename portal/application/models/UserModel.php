<?php

class UserModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function signin($username, $password)
    {
        $user = $this->AllModel->select_one('user', array('username' => $username));
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    function user_detail($user_id)
    {
        $this->db->select('user.*, organization.name organization');
        $this->db->from('user');
        $this->db->join('organization', 'user.organization_id = organization.id');
        $this->db->where('user.id', $user_id);
        $query = $this->db->get();
        return $query->row();
    }

    function update($data, $user_id)
    {
        $this->db->update("user", $data, array("id" => $user_id));
        return ($this->db->affected_rows() > 0);
    }

    function user_list($location = null)
    {
        $this->db->select('user.id, user.username, user.score, user.last_submit, organization.name organization');
        $this->db->from('user');
        $this->db->join('organization', 'user.organization_id = organization.id');
        $this->db->where('user.score >', 0);
        $this->db->where('user.score !=', null);
        if ($location) {
            $this->db->where('organization.location', $location);
        }
        $this->db->order_by('score', 'desc');
        $this->db->order_by('last_submit', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
}
