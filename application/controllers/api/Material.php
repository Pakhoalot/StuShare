<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/17/17
 * Time: 10:11 PM
 */

class Material extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('materials_model');
        $this->load->helper('url');
        $this->cors();


    }
    public function index(){

    }

    public function upload()
    {

        $this->output->set_header("Access-Control-Allow-Origin: * ");

        $name = $this->input->post('name');
        $tag = $this->input->post('tag');
        $description = $this->input->post('description');

        //定义上传文件配置段
        $config['upload_path']      = './uploads/';
        $config['allowed_types']    = 'doc|docx|xls|ppt|pdf|xml|
                                       rar|zip|7z|
                                       txt|
                                       jpg|gif|jpeg|png|
                                       mp3|wav|wma|
                                       avi|mp4|mkv|mov|flv';


        #处理文档位置, 新建文件夹
        if(!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0777, true);

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {

            $error = array('error' => $this->upload->error_msg);

            #接口返回失败信息
            $json = array('status'=> 0,
                            'name'=> $name,
                            'tag' => $tag,
                            'description'=>$description,
                            'message'=> $error['error']);
            echo json_encode($json);


        } else {
            $file = $this->upload->data();

            #链接数据库并把文件路径写到数据库
            $this->materials_model->create_material($file);

            #接口返回成功信息
            $json = array('status'=> 1,
                            'message'=> 'succeed');
            echo json_encode($json);
        }


    }

    /*
     * 接下来是本类用到的private函数
     */
    private function cors(){
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Method:POST,GET");//允许的方法
    }
}