<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/23/17
 * Time: 12:54 PM
 */

class Tags_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function create_tag($tag)
    {
        $query_data = array(
            'tag' => $tag
        );
        $this->db->replace('tag', $query_data);

    }

}