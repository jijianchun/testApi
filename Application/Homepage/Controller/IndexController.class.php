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
    protected $views_model; // 图片表
    public function _initialize() {
      $this->comments_model = M('comments');
      $this->works_model = M('works');
      $this->images_model = M('images');
      $this->views_model = M('views');
    }

    // 记录浏览信息
    public function index()
    {
      $ip = $_SERVER['REMOTE_ADDR'];
      $data['ip'] = $ip;
      $data['time'] = time();

      // 根据ip获取城市信息，并返回浏览总数
      $res = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
      $jsonMatches = array();
      preg_match('#\{.+?\}#', $res, $jsonMatches);
      if(isset($jsonMatches[0])){
        $city_arr = json_decode($jsonMatches[0], true);
        $data['country'] = $city_arr['country'];
        $data['province'] = $city_arr['province'];
        $data['city'] = $city_arr['city'];
      }

      if($this->views_model->add($data) !== false){
        $count = $this->views_model->count();
        $result = array(
          'count' => $count,
          'city' => $data['city']
        );
        // $this->ajaxReturn(array('status'=>1,'msg'=>'添加成功','result'=>$result));
        echo json_encode(array('status'=>1,'msg'=>'添加成功','result'=>$result));
      } else {
        echo json_encode(array('status'=>0,'msg'=>'添加失败'));
      }
    }

    // 作品列表
    public function work_list () {
      $data = $this->works_model->select();
      foreach ($data as $key => $value) {
        foreach ($value as $k => $v) {
          if ($k == 'thumb_url_id') {
            $data[$key]['thumb_img_url'] = 'http://api.jameschun.cc/Public/Uploads'.$this->images_model->where("id=$v")->getField('url');
          } elseif ($k == 'detail_url_id') {
            $data[$key]['detail_img_url'] = 'http://api.jameschun.cc/Public/Uploads'.$this->images_model->where("id=$v")->getField('url');
          }
        }
      }
  		$res = array(
  			'data' => $data
  		);
  		echo json_encode($res);
    }

    // 添加作品
    public function addWork () {
      if (IS_POST) {
  			$req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
        $data['name'] = $req_data['name'];
        $data['thumb_url_id'] = $req_data['thumb_url_id'];
        $data['description'] = $req_data['description'];
        $data['detail_url_id'] = $req_data['detail_url_id'];
        $data['time'] = time();

  			if($this->works_model->add($data) !== false){
  				echo json_encode(array('status'=>1,'msg'=>'添加成功'));
  			} else {
  				echo json_encode(array('status'=>0,'msg'=>'添加失败'));
  			}
  		}
    }

    //编辑作品
    public function editWork () {
      if(IS_POST){
  			$req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
  			$data['id'] = $req_data['id'];
        $data['name'] = $req_data['name'];
        $data['thumb_url_id'] = $req_data['thumb_url_id'];
        $data['description'] = $req_data['description'];
        $data['detail_url_id'] = $req_data['detail_url_id'];

  			$res = $this->works_model->save($data);
  			if($res !== false){
  				echo json_encode(array('status'=>1,'msg'=>'更新成功'));
  			}else{
  				echo json_encode(array('status'=>0,'msg'=>'更新失败'));
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
  			$req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
  			$data['name'] = $req_data['name'];
  			$data['email'] = $req_data['email'];
  			$data['content'] = $req_data['content'];
        $data['time'] = time();

  			if($this->comments_model->add($data) !== false){
  				echo json_encode(array('status'=>1,'msg'=>'添加成功'));
  			} else {
  				echo json_encode(array('status'=>0,'msg'=>'添加失败'));
  			}
  		}
    }
}
