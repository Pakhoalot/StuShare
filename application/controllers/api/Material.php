<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/17/17
 * Time: 10:11 PM
 * @property  util  $util
 */

class Material extends CI_Controller
{
    /**
     * Material constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('materials_model');
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('util');
        $this->util->cors();

    }
    /*
     *
     */
    public function index(){
        echo 'you have accessed this materiral api!';
    }

    /**
     *
     */
    public function upload()
    {

        //获取post信息
        $user['email'] = $this->input->post('email');
        $file_name = $this->input->post('file_name');
        $tag = $this->input->post('tag');
        $category = $this->input->post('category');
        $description = $this->input->post('description');

        //定义上传文件配置段
        $config['upload_path']      = './uploads/' . $user['email'] . '/';
        $config['allowed_types']    = 'doc|docx|xls|ppt|pdf|xml|
                                       rar|zip|7z|
                                       txt|
                                       jpg|gif|jpeg|png|
                                       mp3|wav|wma|
                                       avi|mp4|mkv|mov|flv';

        $config['file_name'] = $file_name;
        $config['max_size'] = 0;//无限制


        #处理文档位置, 新建文件夹
        if(!file_exists($config['upload_path'])){
            $this->create_user_dir($config['upload_path']);

        }

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {

            $error = array('error' => $this->upload->error_msg);

            #接口返回失败信息
            $json = array(
                'status'=> 0,
                'file_name'=> $file_name,
                'tag' => $tag,
                'category' => $category,
                'description'=>$description,
                'message'=> $error['error']);
            echo json_encode($json);


        } else {
            $file = $this->upload->data();

            #链接数据库并把文件路径写到数据库material_attribute
            $material = $this->materials_model->create_material($file, $user['email']);
            #写入material_description
            $this->materials_model->set_material_description($material['id'], $description);
            #写入tag
            $tag_array = explode(',', $tag);
            foreach($tag_array as $tag){
                if($tag == NULL) continue;
                $this->materials_model->set_material_tag($material['id'], $tag);
            }
            #写入category
            $cate_array = explode(',', $category);
            foreach($cate_array as $category){
                if($category == NULL) continue;
                $this->materials_model->set_material_category($material['id'], $category);
            }
            #接口返回成功信息
            $json = array(
                'status'=> 1,
                'message'=> 'succeed',
                'tag' => $tag_array,
                'category'=> $cate_array,
                'description' => $description,
                'file' => array(
                    'material_id' => $material['id'],
                    'file_name' => $material['file_name'],
                    'owner' => $material['owner'],
                    'size' => $material['size'],
                    'type' => $material['type'],
                    'uptime' => $material['uptime'])
                );
            echo json_encode($json);
        }


    }
    /*
     *
     */
    public function download(){
        $material_id = $this->input->post('material_id');
        $material = $this->materials_model->get_material_by_id($material_id);
        if(isset($material['full_path']) && $material['state']=='exist'){
            #添加下载量
            $this->materials_model->download_times_increase($material['id']);
//            force_download($material['full_path'], NULL, true);
            $file_url = substr($material['full_path'],8);
            $json = array(
                'status' => 1,
                'message' => 'succeed',
                'file' => array(
                    'file_url' => $file_url,
                    'material_id' => $material['id'],
                    'file_name' => $material['file_name'],
                    'owner' => $material['owner'],
                    'size' => $material['size'],
                    'type' => $material['type'],
                    'uptime' => $material['uptime'])

            );
            echo json_encode($json);
        }
        else{
            $json = array(
                'status' => 0,
                'message' => 'file_isnt exist',
                'material_id' => $material_id,
            );
            echo json_encode($json);
        }

    }
    /*
     *
     */
    public function show(){
        $sort_by = $this->input->post('sort_by');
        $offset = $this->input->post('offset');
        $total_row = $this->input->post('total_row');
        //默认
        if(!isset($sort_by)) $sort_by = 'time';
        if(!isset($offset) || !isset($total_row) ){
            $json = array(
                'status' => 0,
                'message' => 'para not set',
                'sort_by' => $sort_by,
                'offset' => $offset,
                'total_row' => $total_row
            );
            echo json_encode($json);
            return ;
        }
        $json = array(
            'status' => 1,
            'message' => 'succeed',
            'sort_by' => $sort_by,
            'offset' => $offset,
            'total_row' => $total_row,
            'material_list' => array()
        );
        $result_array = $this->materials_model->get_material_list($sort_by, $offset, $total_row);

        foreach ($result_array as $material){
            $material_detail = $this->materials_model->get_detail_by_id($material['id']);
            $tags = $this->materials_model->get_tag_by_id($material['id']);
            $categorys = $this->materials_model->get_category_by_id($material['id']);
            array_push($json['material_list'], array(
                'material_id'=>$material['id'],
                'file_name'=>$material['file_name'],
                'owner' => $material['owner'],
                'size' => $material['size'],
                'type' => $material['type'],
                'uptime' => $material['uptime'],
                'description'=>$material_detail['description'],
                'likes' => $material_detail['likes'],
                'download_times' => $material_detail['download_times'],
                'tags' => $tags,
                'category'=> $categorys
            ));

        }

        echo json_encode($json);

    }
    /*
     *
     */
    public function set_description(){
        $material_id = $this->input->post('material_id');
        $description = $this->input->post('description');

        #检查material是否存在


        if (!$this->is_exist_by_id($material_id)){
           #构造失败信息
            $json = array(
                'status' => 0,
                'message' => 'no such material',
                'material_id'=> $material_id,
                'description'=> $description
            );
            echo json_encode($json);
            return;
        }

        $this->materials_model->set_material_description($material_id, $description);

        $json = array(
            'status' => 1,
            'message' => 'succeed',
            'material_id'=> $material_id,
            'description'=> $description
        );
        echo json_encode($json);
    }

    public function get_detail_by_id(){
        $id = $this->input->post('material_id');
        $material = $this->materials_model->get_material_by_id($id);
        $material_detail = $this->materials_model->get_detail_by_id($id);

        if(empty($material)||empty($material_detail)){
            $json = array(
                'status'=> 0,
                'message'=> 'no such file',
                'material_id'=>$material['id'],
            );
            echo json_encode($json);
            return ;
        }
        $json = array(
            'status'=> 1,
            'message'=> 'succeed',
            'material_id'=>$material['id'],
            'file_name'=>$material['file_name'],
            'owner' => $material['owner'],
            'size' => $material['size'],
            'type' => $material['type'],
            'uptime' => $material['uptime'],
            'description'=>$material_detail['description'],
            'likes' => $material_detail['likes'],
            'download_times' => $material_detail['download_times']
        );

        echo json_encode($json);
    }
    /*
     *
     */
    public function set_tag(){

        $material_id = $this->input->post('material_id');
        $tag = $this->input->post('tag');

        $tag_array = explode(',', $tag);
        #检查material是否存在
        if (!$this->is_exist_by_id($material_id)){
            #构造失败信息
            $json = array(
                'status' => 0,
                'message' => 'no such material',
                'material_id'=> $material_id,
                'tag'=> $tag_array
            );
            echo json_encode($json);
            return;
        }

        #插入到数据库
        foreach($tag_array as $tag){

            $this->materials_model->set_material_tag($material_id, $tag);
        }
        #构造返回json
        $json = array(
            'status' => 1,
            'message' => 'succeed',
            'material_id' => $material_id,
            'tag' => $tag_array
        );
        echo json_encode($json);

    }

    public function get_file_by_filename_and_email(){
        $filename = $this->input->post('file_name');
        $email = $this->input->post('email');

        $material = $this->materials_model->get_material_by_name_and_owner($filename, $email);

        if(empty($material)){
            $json = array(
                'status' => 0,
                'message' => 'no such file',
            );
            echo json_encode($json);
        }
        #构造返回json
        $json = array(
            'status' => 1,
            'message' => 'succeed',
            'file' => array(
                'material_id' => $material['id'],
                'file_name' => $material['file_name'],
                'owner' => $material['owner'],
                'size' => $material['size'],
                'type' => $material['type'],
                'uptime' => $material['uptime'])
        );
        echo json_encode($json);
    }


    /*
     * 接下来是本类用到的private函数
     */
    private function is_exist_by_id($material_id)
    {
        $material = $this->materials_model->get_material_by_id($material_id);
        return !(empty($material)||$material['state'] != 'exist');
    }

    private function create_user_dir($upload_path)
    {
        mkdir($upload_path, 0777, true);
        $myfile = fopen($upload_path . "index.html", "w");
        $txt = "
<!DOCTYPE html>
    <html>
    <head>
        <title>403 Forbidden</title>
    </head>
    <body>
    
    <p>Directory access is forbidden.</p>
    
    </body>
    </html>
 ";
        fwrite($myfile, $txt);
        fclose($myfile);
    }

}