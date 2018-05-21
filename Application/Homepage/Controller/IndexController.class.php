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
    protected $comment_model;
    public function _initialize() {

      $this->comment_model = M('comments');
    }

    public function index()
    {
        echo 'homepage';
    }

    // 提交保存留言
    public function addComment () {
      if (IS_POST) {
  			// $req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
  			// $data['name'] = $req_data['name'];
  			// $data['email'] = $req_data['email'];
  			// $data['content'] = $req_data['content'];
        // $data['time'] = time();

        $data['name'] = $_POST['name'];
  			$data['email'] = $_POST['email'];
  			$data['content'] = $_POST['content'];
        $data['time'] = date('Y-m-d H:i:s', time());
  			if($this->comment_model->add($data) !== false){
  				echo json_encode(array('status'=>1,'msg'=>'添加成功'));
  			} else {
  				echo json_encode(array('status'=>0,'msg'=>'添加失败'));
  			}
  		}
    }
}
