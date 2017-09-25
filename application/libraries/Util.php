<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/19/17
 * Time: 12:02 PM
 */
Class Util{
    /*
     * 跨域函数
     */
    public static function cors(){
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Method:POST,GET,PATCH,PUT,OPTIONS");//允许的方法
    }

    public static function is_login(){
//        return (isset($_SESSION['logged_in']) && $_SESSION['logged_in']);
        return true;
    }

    public static function get_user_from_session()
    {
        //只有检验过is_login才能使用这个函数
        $user['email'] = $_SESSION['email'];
        $user['nickname'] = $_SESSION['nickname'];
        $user['role'] = $_SESSION['role'];

        return $user;
    }

}
