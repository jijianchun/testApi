<?php
namespace Home\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
 	exit;
}
// header("Content-Type:text/html; charset=utf-8");

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        echo 'hello world';
    }

    // element-tree测试
    public function hello() {
      // http_response_code(201);

      $data = array();
      for($i=1;$i<=2;$i++) {
      	$data[] = array(
      		'id' => $i,
      		'label' => '一级 '.$i
      	);
      }

      $data2 = array();
      for($i=3;$i<=5;$i++) {
      	$data2[] = array(
      		'id' => $i,
      		'label' => '二级 '.($i-2)
      	);
      }

      $data3 = array();
      for($i=6;$i<=1000;$i++) {
      	$data3[] = array(
      		'id' => $i,
      		'label' => '三级 '.($i-5)
      	);
      }

      foreach($data as $key=> $value) {
      	if ($value['id'] == 1) {
      		$data[$key]['children'] = $data2;
      	}
      }

      foreach($data as $key => $value) {
      	foreach($value['children'] as $k=>$v) {
	  		if ($v['id'] == 3) {
	  			$data[$key]['children'][$k]['children'] = $data3;
	  		}
	  	}
      }

      echo json_encode($data);
    }

    // iview-tree测试
    public function hello2() {
    	$data2 = array();
    	for($i=1;$i<=2;$i++) {
	      	$data2[] = array(
	      		'title' => 'parent 1-'.$i,
	      		'expand' => true,
	      	);
	    }

    	$data = array(
    		array(
    			'title' => 'parent 1',
    			'expand' => true,
    			'children' => $data2
    		)
    	);

    	$data3 = array();
    	for($i=1;$i<=1000;$i++) {
    		$data3[] = array(
    			'title' => 'leaf 1-1-'. $i
    		);
    	}

    	foreach($data[0]['children'] as $key => $value) {
    		if ($value['title'] == 'parent 1-1') {
    			$data[0]['children'][$key]['children'] = $data3;
    		}
    	}

    	echo json_encode($data);
    }

    // 大鹏采集
    public function collect() {
      $url = $_GET['urls'];
      if (!$url) {
        echo json_encode(array());
        exit;
      }

      // 接收的可能1个，也可能多个url
      $url_arr = explode(',', $url);

      $output_arr = array();
      foreach($url_arr as $key=>$value) {
        $meta_tags = get_meta_tags($value);
        $str = $meta_tags['keywords'];
        $rule = "/High Quality (.*)/";
        preg_match($rule, $str, $results);
        $arr = explode(',', $results[1]);

        foreach($arr as $key =>$value) {
          $output_arr[] = array(
            'name' => $value
          );
        }
      }

      foreach($output_arr as $key=>$value) {
        $output_arr[$key]['index'] = $key + 1;
      }
      echo json_encode($output_arr);
    }

}
