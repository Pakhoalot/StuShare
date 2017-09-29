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

        $query = $this->db->insert('user_detail', array('email'=>$user['email']));
    }

    public function get_user($email)
    {
        $this->get_user_by_email($email);
    }

    public function get_user_by_email($email){
        $query_data['email'] = $email;
        $query = $this->db->get_where('user_info', $query_data);
        return $query->row_array();
    }
    public function get_detail_by_email($email){
        $query_data['email'] = $email;
        $query = $this->db->get_where('user_detail', $query_data);
        return $query->row_array();
    }
    public function get_all_by_email($email){
        $user = $this->get_user_by_email($email);
        $user_detail = $this->get_detail_by_email($email);
        if(empty($user_detail)){
            $user_detail = array();
        }
        $result = array_merge(
            $user,
            $user_detail
        );
        unset($result['id']);

        return $result;
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

    public function likes_increase($owner)
    {
        $user = $this->get_user($owner);
        $query_data = array(
            'likes' => ++$user['likes']
        );
        $this->db->where('email', $user['email']);
        $this->db->update('user_info', $query_data);
    }


    public function set_user_detail($user_detail)
    {
        $query_data = array(
            'phone' => $user_detail['phone'],
            'adress'=> $user_detail['adress'],
            'grade' => $user_detail['grade'],
        );
        $this->db->where('user_detail', array('email' => $user_detail['email']));
        $this->db->update('user_detail', $query_data);


    }

}