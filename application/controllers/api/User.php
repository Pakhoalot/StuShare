<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/18/17
 * Time: 10:16 AM
 */
include 'Util.php';

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper('cookie');
        Util::cors();
    }

    public function index(){
        echo 'you have accessed user api!';
    }
    /*
     * 注册接口
     */
    public function register(){
        //获取注册信息
        $user['email'] = $this->input->post('email');
        $user['nickname'] = $this->input->post('nickname');
        $user['password'] = $this->input->post('password');


        //检查是否存在用户
        $message = $this->register_check($user);
        if($message == 'OK'){
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
        $user['email'] = $this->input->post('email');
        $user['password'] = $this->input->post('password');

        $message = $this->login_check($user);
        if($message == 'OK'){
            //合法,登录

            //设置session

//            $sessionid = $this->session->session_id;



            $user = $this->users_model->get_user($user['email']);

            $this->set_session($user);
            $json = array(
                'status'=> 1,
                'message'=> $message,
//                'cookie_token'=> $cookie,
//                'ci_session'=> $sessionid
            );
            echo json_encode($json);



        }
        else {
            //密码错误或账户不存在
            $json = array(
                'status'=> 0,
                'message'=> $message,
                'email' => $user['email'],
                'password'=>$user['password']);
            echo json_encode($json);
        }
    }

    public function logout(){
        //销毁session
        $this->session->sess_destroy();
        $json = array('status'=> 1,
            'message'=> 'OK');
        echo json_encode($json);
    }

    public function get_user(){
        if(Util::is_login()){
            $json = array(
                'status'=> 1,
                'message'=> 'logged_in',
                'user' => array(
                    'email' => $_SESSION['email'],
                    'nickname' => $_SESSION['nickname'],
                    'role' => $_SESSION['role'])
            );
            echo json_encode($json);
        }
        else {
            $json = array(
                'status'=> 0,
                'message'=> 'didnt log in'
            );
            echo json_encode($json);
        }
    }
    /*
     *
     *  以下是内部使用的函数，不属于向外暴露的接口
     *
     */
    private function register_check($user){
        $message = NULL;
        if($this->users_model->user_exist($user)){
            $message = 'user exist';
            return $message;
        }
        if($this->users_model->nickname_exist($user)){
            $message = 'nickname exist';
            return $message;
        }
        $message = 'OK';
        return $message;
    }

    private function login_check($user)
    {
        $message = NULL;
        if(!$this->users_model->user_exist($user)){
            $message = 'user not exist';
            return $message;
        }
        if($this->users_model->password_incorrect($user)){
            $message = 'password incorrect';
            return $message;
        }
        $message = 'OK';
        return $message;
    }

    private function set_session($user)
    {
        $_SESSION['email']      = $user['email'];
        $_SESSION['nickname']     = $user['nickname'];
        $_SESSION['logged_in']    = true;
        $_SESSION['role']     = $user['role'];
    }

}