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

    public function create_material($file)
    {
        $user_id = NULL;
        $file_name = $file['file_name'];
        $file_type = $file['file_type'];
        $file_path = $file['file_path'];
        $full_path = $file['full_path'];

        #构造query数组
        $query_data = array(
            'file_name' => $file_name,
            'file_type' => $file_type,
            'file_path' => $file_path,
            'full_path' => $full_path,
            'user_id' => $user_id
        );

        #query
        $query = $this->db->insert('table_name', $query_data);
    }
}