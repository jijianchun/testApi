<?php
namespace Api\Controller;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:x-requested-with,content-type');

use Think\Controller;

class IndexController extends Controller
{
    public function hotArticle()
    {
      $Posts = M('posts');
      $data = $Posts->where('post_status="publish"')->field('post_title,guid')->limit(10)->select();
      echo json_encode($data);
    }

    public function index() {
      echo 'hello, api';
    }
}
