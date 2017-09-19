<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/18/17
 * Time: 10:16 AM
 */

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index(){

    }
    /*
     * 注册接口
     */
    public function register(){
        //获取注册信息
        $user['username'] = $this->input->post('username');
        $user['user_id']    = $this->input->post('user_id');
        $user['password'] = $this->input->post('password');
        $user['phone'] = $this->input->post('phone');
        $user['type'] = $this->input->post('type');

        //检查是否存在用户
        $message = $this->check($user);
        if($message != 'OK'){
            //合法，可以注册
            $result = $this->users_model->create_user($user);
            $json = array('status'=> 1,
                'message'=> $message);
            echo json_encode($json);

        }
        else {
            //用户已存在或这什么滴
            $json = array('status'=> 0,
                'message'=> $message);
            echo json_encode($json);
        }
    }

    public function login(){

    }

    public function logout(){
        //销毁session
        $this->session->sess_destroy();
    }


    /*
     *
     *  以下是内部使用的函数，不属于向外暴露的接口
     *
     */
    private function check($user){
        $message = NULL;
        if(!$this->users_model->is_exist($user)){
            $message = 'user exist';
            return $message;
        }
        if(!$this->users_model->username_exist($user)){

        }
        return $message;
    }

    private function is_login(){
        if($this->session->userdata('session_id')){

        } else {

        }
    }
}