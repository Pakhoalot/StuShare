<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/25/17
 * Time: 5:10 PM
 */

class Search_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function search_material_name($search_key)
    {
        $this->db->like('file_name', $search_key);
        $query = $this->db->get('material_attribute');
        $material_id_array = array();
        foreach($query->result_array() as $material){
            array_push($material_id_array, $material['id']);
        }
        return $material_id_array;
    }

    public function search_material_tag($search_key)
    {

        $this->db->like('tag', $search_key);
        $query = $this->db->get('material_tag');
        $material_id_array = array();
        foreach($query->result_array() as $material_tag){
            array_push($material_id_array, $material_tag['material_id']);
        }
        return $material_id_array;
    }

    public function count_search_key($search_key)
    {
        $query = $this->db->get_where('search_key', array('search_key'=>$search_key));

        if(empty($query->row_array())){
            $query_data = array(
                'search_key'=>$search_key,
            );
            $this->db->insert('search_key', $query_data);
        }

        $key = $this->db->get_where('search_key', array('search_key'=>$search_key));
        $query_data = array(
            'quote_times' => ++$key->row_array()['quote_times']
        );
        $this->db->where('search_key', $search_key);
        $this->db->update('search_key', $query_data);

    }

    public function search_user_email($search_key){

        $this->db->like('email', $search_key);
        $query = $this->db->get('user_info');
        $email_array = array();
        foreach($query->result_array() as $user){
            array_push($email_array, $user['email']);
        }
        return $email_array;
    }

    public function search_user_nickname($search_key){

        $this->db->like('nickname', $search_key);
        $query = $this->db->get('user_info');
        $email_array = array();
        foreach($query->result_array() as $user){
            array_push($email_array, $user['email']);
        }
        return $email_array;
    }




}