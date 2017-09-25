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
        $query_data = array(
            'file_name' => $search_key
        );
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
        $query_data = array(
            'tag' => $search_key
        );
        $this->db->like('tag', $search_key);
        $query = $this->db->get('material_tag');
        $material_id_array = array();
        foreach($query->result_array() as $material_tag){
            array_push($material_id_array, $material_tag['material_id']);
        }
        return $material_id_array;
    }


}