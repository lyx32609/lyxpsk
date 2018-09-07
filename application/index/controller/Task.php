<?php
namespace app\pxskhome\controller;
use think\Controller;
use app\pxskhome\model\Task as TaskModel;
use app\pxskhome\model\Timg;
class Task extends Qingqiu{//Qingqiu{//
	function index(){
 		$lat=$this->post['latitude'];//39.871091//http://www.thinkphp.cn/extend/561.html
 		$lng=$this->post['longitude'];//116.386159
		// $lat=39.871091;//39.871091//http://www.thinkphp.cn/extend/561.html
		// $lng=116.386159;
		$task = new TaskModel();//M('task');
		$taskarr=TaskModel::with('timgs,business')
// 			->field('pxsk_task.id,pxsk_task.bid,pxsk_task.taskinte,pxsk_task.createtime,pxsk_task.msg,
// 					pxsk_task.latitude,pxsk_task.longitude,GROUP_CONCAT(t.taskimg) taskimg,
// 					GROUP_CONCAT(t.thumbimg) thumbimg,b.bname,b.img')
// 			->join('pxsk_business b ON b.id = pxsk_task.bid')
// 			->group('t.tid')
			->select();//dump($taskarr);die;
		$taskarr=collection($taskarr)->toArray();
		//$taskarr=imglistts($taskarr,'taskimg','thumbimg');//dump($taskarr);die;
		$arr=array();
		foreach ($taskarr as $v){
			$v['distance']=round(getDistance($lat,$lng,$v['latitude'], $v['longitude']));
			//$v['sendtime'] = 100;//距离发布时间
			$nowday=strtotime(date('Y-m-d'));//今日凌晨
			$intime=timediff($v['createtime'],time());
			//返回数组array(4) { ["day"]=> int(0) ["hour"]=> int(0) ["min"]=> int(1) ["sec"]=> int(90) }
			if ($intime['day'] >7) {
				$v['sendtime'] ='7天前';
			}elseif($intime['day'] >=3){
				$v['sendtime'] ='3天前';
			}elseif($intime['day'] >=1){
				$v['sendtime'] ='前天';
			}elseif($v['createtime'] < $nowday){//小任务显示时间为自然时间状态,昨天发的就显示昨天今天发的显示几个小时前
				$v['sendtime'] ='昨天';
			}elseif($intime['hour'] > 1){
				$v['sendtime'] = $intime['hour'].'小时前';
			}elseif($intime['min'] >1){
				$v['sendtime'] = $intime['min'].'分钟前';
			}else{
				$v['sendtime'] = '1分钟内';
			}
			$arr[]=$v;
		}//dump($arr);die;
		$arr = my_sort($arr, 'distance', SORT_ASC,SORT_NUMERIC);
		//$page=30*($this->post['page'] -1);
		$result=array_slice($arr,0,30);//$result=array_slice($arr,$page,30);
		$data ['code'] = 1;
		$data ['data'] = $result;
		return json($data);
	}
	
	function screen(){//筛选    传给我经纬度和时间区域1天内，3天，7天
		$lat=$this->post['latitude'];//39.871091//http://www.thinkphp.cn/extend/561.html
		$lng=$this->post['longitude'];//116.386159
		$nowday=$this->post['time'];
// 		$nowday=7;
// 		$lat=39.871091;//39.871091//http://www.thinkphp.cn/extend/561.html
// 		$lng=116.386159;
		$today=strtotime(date('Y-m-d'));//今日凌晨
		if (!$lat) {
			$data ['code'] = 0;
			$data ['error_msg'] = '网络不稳定，无法获取经纬度 ';
			return json($data);
		}
		//$task = M('task');
		$taskarr=TaskModel::with('timgs,business')
// 			->field('pxsk_task.id,pxsk_task.bid,pxsk_task.taskinte,pxsk_task.createtime,pxsk_task.msg,
// 					pxsk_task.latitude,pxsk_task.longitude,GROUP_CONCAT(t.taskimg) taskimg,
// 					GROUP_CONCAT(t.thumbimg) thumbimg,b.bname,b.img')
// 			->join('pxsk_business b ON b.id = pxsk_task.bid')
// 			->group('t.tid')
			->select();//dump($taskarr);die;
		$arr=array();
		foreach ($taskarr as $v){
			//$v['sendtime'] = 100;//距离发布时间
			$v['distance']=round(getDistance($lat,$lng,$v['latitude'], $v['longitude']));
			$intime=timediff($v['createtime'],time());
			//返回数组array(4) { ["day"]=> int(0) ["hour"]=> int(0) ["min"]=> int(1) ["sec"]=> int(90) }
			if ($intime['day'] < $nowday) {
				if ($intime['day'] >= 3) {
					$v['sendtime'] ='7天内';
				}elseif($intime['day'] >= 1){
					$v['sendtime'] ='3天内';
				}elseif($v['createtime'] < $today){//小任务显示时间为自然时间状态,昨天发的就显示昨天今天发的显示几个小时前
					$v['sendtime'] ='昨天';
				}elseif($intime['hour'] > 1){
					$v['sendtime'] = $intime['hour'].'小时前';
				}elseif($intime['min'] >1){
					$v['sendtime'] = $intime['min'].'分钟前';
				}else{
					$v['sendtime'] = '1分钟内';
				}
				$arr[]=$v;
			}
			
		}//var_dump($arr);die;
		if (empty($arr)) {
			$data ['code'] = 1;
			$data ['data'] = [];
			return json($data);
		}
		$arr = my_sort($arr, 'distance', SORT_ASC,SORT_NUMERIC);
		$page=30*($this->post['page'] -1);
		$result=array_slice($arr,0,30);//$result=array_slice($arr,$page,30);
		$data ['code'] = 1;
		$data ['data'] = $result;
		return json($data);
		
	}
	
	function ots(){//137
		$time1=array('time'=>1);
		$time2=array('time'=>3);
		$time3=array('time'=>7);
		$time=array($time1,$time2,$time3);
		$data ['code'] = 1;
		$data ['data'] = $time;
		return json($data);
	}
	
	function add(){
		$btoken=$this->bid;
		$this->post['bid']=$btoken;
// 		$business=M('business');
		$bures=db('business')->where("id=$btoken")->find();
		if ($bures['integral'] < $this->post['taskinte']) {
			$data ['code'] = 0;
			$data ['error_msg'] = '您的积分不足，请重新设置小任务积分！ ';
			return json($data);
		}
		$this->post['createtime']=time();
// 		$task=M('task');
		$res=db('task')->insertGetId($this->post);//添加
		$nid=$res;	
		$this->post['tid']=$nid;
		if (isset($this->post['img'])) {
			
			$firstLetterDir=$_SERVER['DOCUMENT_ROOT'].'/uploads/business/'.$btoken.'/task';
			
			if (!file_exists($firstLetterDir)||!is_dir($firstLetterDir)){
				mkdir($firstLetterDir,0777,true);
			}
			$imgs=explode(',', $_POST['img']);//dump($imgs);die;
			foreach ($imgs  as $k=>$v){
				$savePath =  $firstLetterDir.'/task';
				$filename =date("ymdHis");
				$file=$savePath."/".$filename.".png";//图片存路径
				$myfile="/uploads/business/".$btoken."/task/".$filename.".png";//图片入库路径
				//写入文件
				$string =base64_decode($this->post['img']);
				file_put_contents($file,$string);
	// 			$bimg=M('timg');
				$this->post['taskimg']=$myfile; 
				$timg=new Timg();
				$re=$timg->allowField(true)->insert($this->post);
			}
			if($re && $res){
				$data ['code'] = 1;
				return json($data);
			}else{
				$data ['code'] = 0;
				$data ['error_msg'] = '添加失败！请您重新添加！ ';
				return json($data);
			}
		}
	}
	function update(){
		//数据入表
		$upload=new \Think\Upload();
		//2、设置配置项
		//允许的类型
		$id=$this->bid;
		$upload->mimes=array('image/png','image/gif','image/jpeg','image/pjpeg');
		$upload->maxSize=2000000;//大小
		$upload->rootPath="./public/uploads/";//保存的根目录
		$upload->savePath =  './task/'.$id.'/';// 设置附件上传目录
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/public/uploads')||!is_dir($_SERVER['DOCUMENT_ROOT'].'/public/uploads')){
			mkdir($_SERVER['DOCUMENT_ROOT'].'/public/uploads',0777);
		}
		$firstLetterDir=$_SERVER['DOCUMENT_ROOT'].'/public/uploads/task';
		if (!file_exists($firstLetterDir)||!is_dir($firstLetterDir)){
			mkdir($firstLetterDir,0777);
		}
		if (!file_exists($firstLetterDir.'/'.$id)||!is_dir($firstLetterDir.'/'.$id)){
			mkdir($firstLetterDir.'/'.$id,0777);
		}
		//把子目录关闭
		$upload->autoSub=false;
		$upload->saveName=array('uniqid',array(mt_rand(1000,9999),true));
		//3、保存文件
		if(!$upload->upload()){
	    $this->error($upload->getErrorMsg());die;//输出错误提示
		}else{
	    $info = $upload->getUploadFileInfo(); //取得成功上传的文件信息
	    	foreach($info as $key => $value){
	        $data[$key]['path'] = '{本地路径}'.$value['savename'];//这里以获取在本地的保存路径为例
	   		}
		}
	}
	
	function deltask(){//删除动态
		$task=M('task');
		$this->post['bid']=1;
		if($task->create()){
			$re=$task->where($this->post['createtime'])->delete();
			if($re){
				//$this->success("注册成功",U("Home/Business/register"),3);
				$data ['code'] = 1;
				return json($data);
			}else{
				//$this->error("注册失败",U("Home/Business/register"),3);
				$data ['code'] = 0;
				$data ['error_msg'] = '删除失败，请重新删除';
				return json($data);
			}
		}
	
	}
	
	function reply(){//回复评论
		$task=M('reply');
		$this->post['bid']=1;
		if($task->create()){
			$re=$task->where($this->post['createtime'])->delete();
			if($re){
				//$this->success("注册成功",U("Home/Business/register"),3);
				$data ['code'] = 1;
				return json($data);
			}else{
				//$this->error("注册失败",U("Home/Business/register"),3);
				$data ['code'] = 0;
				$data ['error_msg'] = '删除失败，请重新删除';
				return json($data);
			}
		}
	
	}
	
}