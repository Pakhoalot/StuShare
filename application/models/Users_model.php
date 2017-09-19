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
        $query = $this->db->insert('table_name', $query_data);
    }

    public function is_exist($user)
    {
        $query_data = array(
            $user_id = $user['user_id']
        );
        $query = $this->db->get_where('table_name',$query_data);
        if (empty($query->result_array()))
            return false;
        return true;

    }

}