<?php
namespace Api\Controller;
header('Access-Control-Allow-Origin:*');
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
		// if (IS_POST) {
		// 	$data['name'] = I('post.name');
		// 	$data['location'] = I('post.location');
		// 	$data['city'] = I('post.city');
		// }
		$data['name'] = 'bosh';
		$data['location'] = '小前锋';
		$data['city'] = '洛杉矶';

		if($this->player_model->add($data) !== false){
			echo json_encode(array('status'=>1,'msg'=>'添加成功'));
		} else {
			echo json_encode(array('status'=>0,'msg'=>'添加失败'));
		}
	}

	// 查询
    public function query(){
    	if($_GET['name']) {
    		$where['name'] = array('like', '%'.$_GET['name']+'%');
    	}
    	if($_GET['location']) {
    		$where['location'] = $_GET['location'];
    	}
    	if($_GET['city']) {
    		$where['city'] = $_GET['city'];
    	}

    	// 分页参数
    	$pageSize = $_GET['pageSize'];
    	$pageNo = $_GET['pageNo'];
    	$start = $pageSize * $pageNo;

		$data = $this->player_model->where($where)->limit($start,$pageSize)->select();
		echo json_encode($data);
    }

    public function index() {
      echo 'hello, api';
    }
}






















