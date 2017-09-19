<?php
/**
 * Created by PhpStorm.
 * User: pakholeung
 * Date: 9/19/17
 * Time: 12:02 PM
 */
function cors(){
    header("Access-Control-Allow-Origin: * ");
    header("Access-Control-Allow-Method:POST,GET,PATCH,PUT,OPTIONS");//允许的方法
}