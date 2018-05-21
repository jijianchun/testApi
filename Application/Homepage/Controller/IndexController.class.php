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
    protected $comments_model; // 评论表
    protected $works_model; // 作品表
    protected $images_model; // 图片表
    public function _initialize() {
      $this->comments_model = M('comments');
      $this->works_model = M('works');
      $this->images_model = M('images');
    }

    public function index()
    {
        echo 'homepage';
    }

    // 添加作品
    public function addWork () {
      if (IS_POST) {
  			// $req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
  			// $data['name'] = $req_data['name'];
  			// $data['email'] = $req_data['email'];
  			// $data['content'] = $req_data['content'];
        // $data['time'] = time();

        $data['name'] = $_POST['name'];
  			$data['thumb_url_id'] = $_POST['thumb_url_id'];
  			$data['description'] = $_POST['description'];
  			$data['detail_url_id'] = $_POST['detail_url_id'];
        $data['time'] = time();
  			if($this->works_model->add($data) !== false){
  				echo json_encode(array('status'=>1,'msg'=>'添加成功'));
  			} else {
  				echo json_encode(array('status'=>0,'msg'=>'添加失败'));
  			}
  		}
    }

    // 作品封面图上传
    public function upload () {
      $upload = new \Think\Upload();// 实例化上传类
      $upload->maxSize   =     3145728 ;// 设置附件上传大小
      $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
      $upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
      $upload->savePath  =     '/thumb/'; // 设置附件上传（子）目录
      // 上传文件
      $info = $upload->upload();
      if(!$info) {// 上传错误提示错误信息
          $this->error($upload->getError());
      }else{// 上传成功
        $thumb_info = $info['thumb_url'];
        // echo $thumb_info['savepath'].$thumb_info['savename'];
        $data['url'] = $thumb_info['savepath'].$thumb_info['savename'];
        $data['type'] = 'thumb';
        $data['time'] = time();
        $result = $this->images_model->add($data);
        if ($result) {
          echo json_encode(array('status'=>1,'msg'=>'添加成功','img_id'=>$result));
        } else {
          echo json_encode(array('status'=>0,'msg'=>'添加失败'));
        }
      }
    }

    // 详情图上传
    public function detail_upload () {
      $upload = new \Think\Upload();// 实例化上传类
      $upload->maxSize   =     3145728 ;// 设置附件上传大小
      $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
      $upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
      $upload->savePath  =     '/detail/'; // 设置附件上传（子）目录
      // 上传文件
      $info = $upload->upload();
      if(!$info) {// 上传错误提示错误信息
          $this->error($upload->getError());
      }else{// 上传成功
        $thumb_info = $info['detail_url'];
        // echo $thumb_info['savepath'].$thumb_info['savename'];
        $data['url'] = $thumb_info['savepath'].$thumb_info['savename'];
        $data['type'] = 'detail';
        $data['time'] = time();
        $result = $this->images_model->add($data);
        if ($result) {
          echo json_encode(array('status'=>1,'msg'=>'添加成功','img_id'=>$result));
        } else {
          echo json_encode(array('status'=>0,'msg'=>'添加失败'));
        }
      }
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
        $data['time'] = time();
  			if($this->comments_model->add($data) !== false){
  				echo json_encode(array('status'=>1,'msg'=>'添加成功'));
  			} else {
  				echo json_encode(array('status'=>0,'msg'=>'添加失败'));
  			}
  		}
    }
}
