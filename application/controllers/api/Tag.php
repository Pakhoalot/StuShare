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
    /*
     *
     */
    public function create(){
        $tag = $this->input->post('tag');
        $tag_array = explode(',', $tag);
        foreach($tag_array as $tag){
            $this->tags_model->create_tag($tag);
        }

        $json = array(
            'status' => 1,
            'message' => 'succeed',
            'tag'=> $tag_array,
        );
        echo json_encode($json);
    }
    /*
     *
     */
    public function get_all_tag(){
        $tag = $this->tags_model->get_all_tag();

        $json = array(
            'status' => 1,
            'message' => 'succeed',
            'tag_list' => array()
        );
        foreach($tag as $t){
            array_push($json['tag_list'], array(
                'tag_id' => $t['id'],
                'tag' => $t['tag'],
            ));
        }
        echo json_encode($json);
    }

}