<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/25/17
 * Time: 12:12 AM
 */

class Categorys_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function create_category($category)
    {
        $query_data = array(
            'cate_name' => $category
        );
        $this->db->replace('category', $query_data);

    }

    public function get_all_category()
    {
        $query = $this->db->get('category');
        return $query->result_array();
    }
}