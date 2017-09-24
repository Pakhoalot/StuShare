<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/25/17
 * Time: 12:11 AM
 */
include 'Util.php';

class Category extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('categorys_model');
        Util::cors();
    }

    /*
     *
     */
    public function create(){
        $category = $this->input->post('category');
        $category_array = explode(',', $category);
        foreach($category_array as $category){
            $this->categorys_model->create_category($category);
        }

        $json = array(
            'status' => 1,
            'message' => 'succeed',
            'category'=> $category_array,
        );
        echo json_encode($json);
    }
    /*
     *
     */
    public function get_all_category(){
        $categorys = $this->categorys_model->get_all_category();

        $json = array(
            'status' => 1,
            'message' => 'succeed',
            'category_list' => array()
        );
        foreach($categorys as $category){
            array_push($json['category_list'], array(
                'category_id' => $category['id'],
                'category' => $category['category'],
            ));
        }
        echo json_encode($json);
    }
}