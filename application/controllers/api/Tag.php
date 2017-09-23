<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/23/17
 * Time: 12:53 PM
 */
include 'Util.php';

class Tag extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tags_model');
        Util::cors();
    }

    public function add(){
        $tag = $this->input->post('tag');
        $tag_array = explode(',', $tag);
        foreach($tag_array as $tag){
            $this->tags_model->create_tag($tag);
        }
    }

}