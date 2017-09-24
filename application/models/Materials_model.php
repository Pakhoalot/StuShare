<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/17/17
 * Time: 10:20 PM
 */


class Materials_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function create_material($file, $email)
    {

        #构造query数组
        $query_data = array(
            'file_name' => $file['file_name'],
            'owner' => $email,
            'file_path' => $file['file_path'],
            'full_path' => $file['full_path'],
            'size' => $file['file_size'],
            'type' => $file['file_type'],
            'state' => 'exist',
        );

        #query
        $query = $this->db->insert('material_attribute', $query_data);
        $material = $this->get_material_by_name_and_owner($file['file_name'], $email);
        return $material;
    }

    public function get_material_by_name_and_owner($file_name, $owner)
    {
        $query_data = array(
            'file_name' => $file_name,
            'owner' => $owner
        );

        #query
        $query = $this->db->get_where('material_attribute', $query_data);
        return $query->row_array();
    }

    public function get_material_by_id($file_id)
    {
        $query_data = array(
            'id' => $file_id
        );

        #query
        $query = $this->db->get_where('material_attribute', $query_data);
        return $query->row_array();
    }

    public function get_material_list($sort_by, $offset, $total_row)
    {
        if($sort_by == 'time'){
            $this->db->order_by('uptime');
        }
        $query_data = array(
            'state' => 'exist'
        );
        $this->db->limit($total_row, $offset);
        $query = $this->db->get_where('material_attribute', $query_data);
        return $query->result_array();
    }

    public function set_material_description($material_id, $description)
    {
        $query_data = array(
            'description' => $description
        );
        $this->db->where('material_id', $material_id);
        $this->db->update('material_detail', $query_data);

    }

    public function set_material_tag($material_id, $tag)
    {
        $this->create_tag($tag);
        $query_data = array(
            'material_id'=>$material_id,
            'tag' => $tag
        );
        $query = $this->db->get_where('material_tag', $query_data);
        if(empty($query->row_array())){
            $this->db->insert('material_tag', $query_data);
        }
    }

    public function create_tag($tag)
    {
        $query_data = array(
            'tag' => $tag
        );
        $this->db->replace('tag', $query_data);

    }

    public function get_detail_by_id($id)
    {
        $query_data = array(
            'material_id' => $id,
        );
        $query = $this->db->get_where('material_detail', $query_data);
        return $query->row_array();
    }

    public function download_times_increase($id)
    {
        $material_detail = $this->get_detail_by_id($id);
        $query_data = array(
            'download_times' => ++$material_detail['download_times']
        );
        $this->db->where('material_id', $id);
        $this->db->update('material_detail', $query_data);

    }


}