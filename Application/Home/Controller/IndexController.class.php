<?php
namespace Home\Controller;
header("Content-Type:text/html; charset=utf-8");

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        echo 'hello world';
    }
    
}
