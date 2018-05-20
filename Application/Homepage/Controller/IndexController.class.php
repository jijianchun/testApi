<?php
namespace Homepage\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
 	exit;
}

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        echo 'homepage';
    }

    // 提交留言
    public funtion addComment () {

    }
}