<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/18/17
 * Time: 10:43 AM
 */

class Users_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function create_user($user){
        #构造query数组
        $query_data = $user;
        $query_data['credit'] = 0;
        $query_data['role'] = 1;
        $query_data['likes'] = 0;
        $query = $this->db->insert('user_info', $query_data);
    }

    public function get_user($email)
    {
        $query_data['email'] = $email;
        $query = $this->db->get_where('user_info', $query_data);
        return $query->row_array();
    }

    public function user_exist($user)
    {
        $query_data = array(
            'email' => $user['email']
        );
        $query = $this->db->get_where('user_info', $query_data);
        if (empty($query->result_array()))
            return false;
        return true;

    }
    public function nickname_exist($user)
    {
        $query_data = array(
            'nickname' => $user['nickname']
        );
        $query = $this->db->get_where('user_info',$query_data);
        if (empty($query->result_array()))
            return false;
        return true;

    }

    public function password_incorrect($user)
    {
        $query_data = array(
            'email' => $user['email'],
            'password' => $user['password']
        );

        $query = $this->db->get_where('user_info', $query_data);
        if(empty($query->result_array()))
            return true;
        return false;
    }

}