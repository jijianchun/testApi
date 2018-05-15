<?php
namespace Api\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE");
header('Access-Control-Allow-Headers:x-requested-with,content-type');

use Think\Controller;

class IndexController extends Controller
{
	protected $player_model;

	public function _initialize() {

		// parent::_initialize();
		$this->player_model = M('user');
		// $this->player_model = D('Players')
	}

	// 添加球员
	public function add(){
		if (IS_POST) {
			$req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
			$data['name'] = $req_data['name'];
			$data['location'] = $req_data['location'];
			$data['city'] = $req_data['city'];

			if($this->player_model->add($data) !== false){
				echo json_encode(array('status'=>1,'msg'=>'添加成功'));
			} else {
				echo json_encode(array('status'=>0,'msg'=>'添加失败'));
			}
		}
	}

	// 查询
    public function query(){
    	if($_GET['name']) {
    		$where['name'] = array('like', '%'.$_GET['name'].'%');
    	}
    	if($_GET['location']) {
    		$where['location'] = array('like', '%'.$_GET['location'].'%');
    	}
    	if($_GET['city']) {
    		$where['city'] = array('like', '%'.$_GET['city'].'%');
    	}

    	// 分页参数
    	$pageSize = $_GET['pageSize'];
    	$pageNo = intVal($_GET['pageNo']) - 1;
    	$start = $pageSize * $pageNo;

		$data = $this->player_model->where($where)->limit($start,$pageSize)->select();
		$count = $this->player_model->where($where)->limit($start,$pageSize)->count();
		$res = array(
			'data' => $data,
			'total' => intVal($count)
		);
		echo json_encode($res);
	}

	// 删除球员信息
	public function del(){
		if($_GET['id']){
			$where['id'] = $_GET['id'];
		}
		$result = $this->player_model->where($where)->delete();
		if($result){
			echo json_encode(array('status'=>1,'msg'=>'删除成功'));
		}else{
			echo json_encode(array('status'=>0,'msg'=>'删除失败'));
		}
	}

	// 编辑球员信息
	public function edit(){
		if(IS_POST){
			$req_data = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
			$data['id'] = $req_data['id'];
			$data['name'] = $req_data['name'];
			$data['city'] = $req_data['city'];
			$data['location'] = $req_data['location'];

			$res = $this->player_model->save($data);
			if($res !== false){
				echo json_encode(array('status'=>1,'msg'=>'更新成功'));
			}else{
				echo json_encode(array('status'=>0,'msg'=>'更新失败'));
			}
		}
	}

    public function index() {
      echo 'hello, api';
    }
}
