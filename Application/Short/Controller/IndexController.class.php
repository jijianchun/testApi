<?php
namespace Short\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
 	exit;
}

use Think\Controller;

class IndexController extends Controller
{
  protected $category_model;
  protected $content_model;

	public function _initialize() {
		$this->category_model = M('category');
		$this->content_model = M('content');
	}

  public function index()
  {
      echo 'short';
  }

  // 添加快捷键
  public function add(){
    if (IS_POST) {
      $req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
      $data['category_id'] = $req_data['category_id'];
      $data['name'] = $req_data['name'];
      $data['description'] = $req_data['description'];
      $time = time();
    }

    if($this->content_model->add($data) !== false){
      echo json_encode(array('status'=>1,'msg'=>'添加成功'));
    } else {
      echo json_encode(array('status'=>0,'msg'=>'添加失败'));
    }
  }


  // 修改快捷键
  // if($_REQUEST['m'] == 'modify'){
  //   $id = $_REQUEST['id'];
  //   $category_id = $_REQUEST['category_id'];
  //   $name = $_REQUEST['name'];
  //   $description = $_REQUEST['description'];
  //   $sql = "update content set name='$name',description='$description',category_id='$category_id' where id='$id'";
  //   if(!mysql_query($sql,$con)){
  //     echo json_encode(array('status'=>false,'msg'=>mysql_error()));
  //     exit;
  //   }
  //   echo json_encode(array('status'=>true));
  // }
  //
  // // 删除快捷键
  // if($_REQUEST['m'] == 'del'){
  //   $id = $_REQUEST['id'];
  //   $sql = "delete from content where id='$id'";
  //   if(!mysql_query($sql,$con)){
  //     echo json_encode(array('status'=>false,'msg'=>mysql_error()));
  //     exit;
  //   }
  //   echo json_encode(array('status'=>true));
  // }

  // 查询分类列表
  public function query(){
    if($_GET['category_id']) {
  		$where['category_id'] = array('eq', $_GET['category_id']);
  	}
    // 分页参数
    $pageSize = $_GET['pageSize'];
    $pageNo = intVal($_GET['pageNo']) - 1;
    $start = $pageSize * $pageNo;

    $data = $this->content_model->where($where)->limit($start,$pageSize)->select();
    $count = $this->content_model->where($where)->limit($start,$pageSize)->count();
    $res = array(
      'status' => true,
      'data' => $data,
      'total' => intVal($count)
    );
    echo json_encode($res);
  }

  // 查询分类列表
  public function query_category_list(){
    // 分页参数
    $pageSize = $_GET['pageSize'];
    $pageNo = intVal($_GET['pageNo']) - 1;
    $start = $pageSize * $pageNo;

    if($pageSize){
      $data = $this->category_model->limit($start,$pageSize)->select();
    } else {
      $data = $this->category_model->select();
    }

    $data = $this->category_model->select();
    $count = $this->category_model->count();
    $res = array(
      'status' => true,
      'data' => $data,
      'total' => intVal($count)
    );
    echo json_encode($res);
  }

  // 添加分类
  public function cate_add(){
    if (IS_POST) {
			$req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
			$data['name'] = $req_data['name'];
			$time = time();

			if($this->category_model->add($data) !== false){
				echo json_encode(array('status'=>1,'msg'=>'添加成功'));
			} else {
				echo json_encode(array('status'=>0,'msg'=>'添加失败'));
			}
		}
  }

  // 修改分类
  public function cate_modify(){
    if(IS_POST){
			$req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
			$data['id'] = $req_data['id'];
			$data['name'] = $req_data['name'];

			$res = $this->category_model->save($data);
			if($res !== false){
				echo json_encode(array('status'=>1,'msg'=>'更新成功'));
			}else{
				echo json_encode(array('status'=>0,'msg'=>'更新失败'));
			}
		}
  }

  // 删除分类
  public function cate_del(){
    if($_GET['id']){
			$where['category_id'] = $_GET['id'];
      $cate_where['id'] = $_GET['id'];
		}
    // 判断该分类下是否有关联内容
    $count = $this->content_model->where($where)->count();
    if($total > 0) {
      echo json_encode(array('status'=>0,'msg'=>'该分类下有关联快捷键，请先删除分类下所有快捷键再尝试'));
      exit;
    }
		$result = $this->category_model->where($cate_where)->delete();
		if($result){
			echo json_encode(array('status'=>1,'msg'=>'删除成功'));
		}else{
			echo json_encode(array('status'=>0,'msg'=>'删除失败'));
		}
  }

}
