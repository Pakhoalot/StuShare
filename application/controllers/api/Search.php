<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/24/17
 * Time: 11:07 AM
 * @property  util $util
 */

class Search extends CI_Controller
{
    /**
     * Search constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('util');
        $this->util->cors();
    }

    public function index(){
        $this->all();
    }

    public function all(){
        $this->material();

    }

    public function material(){
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
            #统计关键字,建立热门搜索
            $this->search_model->count_search_key($search_key);
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

    public function user(){
        //获取搜索关键字
        $content = $this->input->post('content');
        $search_key_array = explode(' ', $content);

        $this->load->model('search_model');
        $this->load->model('users_model');

        $json = array(
            'status'=> 1,
            'message'=> 'succeed',
            'result'=> array()
        );

        foreach($search_key_array as $search_key){
            $email_array1 = $this->search_model->search_user_email($search_key);
            $email_array2 = $this->search_model->search_user_nickname($search_key);
            $email_array = array_merge($email_array1, $email_array2);
            $email_array = array_unique($email_array);
            foreach($email_array as $email){
                $result = $this->users_model->get_all_by_email($email);
                array_push($json['result'], $result);
            }
        }

        echo json_encode($json);

    }
}