<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/24/17
 * Time: 11:07 AM
 */

class Search extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->all();
    }

    public function all(){
        //获取搜索关键字
        $content = $this->input->post('content');
        $search_key_array = explode(' ', $content);

        $this->load->model('search_model');
        $this->load->model('materials_model');

        $json = array(
            'status'=> 1,
            'message'=> 'succeed',
            'result'=> array()
        );
        foreach($search_key_array as $search_key){
            $material_id_array1 = $this->search_model->search_material_name($search_key);
            $material_id_array2 = $this->search_model->search_material_tag($search_key);
            $material_id_array = array_merge($material_id_array1, $material_id_array2);
            $material_id_array = array_unique($material_id_array);
            foreach($material_id_array as $material_id){
                $result = $this->materials_model->get_all_by_id($material_id);
                array_push($json['result'], $result);
            }
        }

        echo json_encode($json);

    }

    public function material(){

    }

    public function user(){

    }
}