<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
use app\index\model\Session;
use think\helper\Time;
use app\index\model\Group;
class Login extends Controller{
	function index(){
		$user=new User();
		if (request()->isPost()) {//request()->isPost()
			//return json(1);
			$post=input('post.');
			// $post['tel']=1;
			// $post['password']=1;
			$tel=trim($post['tel']);
			$password= trim($post['password']);
			$istel=$user->where("tel=$tel")->find();//return json($istel);
			if ($istel) {
				$where['tel'] = $tel;
				$where['password'] = Md5($password);
				$isuser=$user->where($where)->find();
				if($isuser){
					//$session=M('session');
					$sessionarr['user_id']=$isuser['user_id'];
					//$business=M('business');
					$time=time()+2592000;//用户一个月不登录就过期2592000
					$token=base64_encode($istel['tel'].'user'.$time);
					$sessionarr['session_tel']=$isuser['tel'];
					$session=new Session();
					$issession=$session->where("session_tel=".$tel)->find();//判断是否存在session
					$sessionarr['token']=$token;
					$sessionarr['expires']=$time;
					if ($issession) {
						$result=$session->allowField(true)->save($sessionarr,['session_id' => $issession['session_id']]);
					}else{;
					$sessionarr['create_time']=time();
					$session->data($sessionarr);
					$result=$session->save();
					}
					if ($result) {
						$data ['code'] = 1001;
						$data ['token'] =$token;
						$data ['msg'] = '登录成功';
						$data ['data'] = $isuser;
						return json($data);
					}
					$data ['code'] = 1005;
					$data ['token'] ="";
					$data ['msg'] = '登录失败';
					$data ['data'] = (Object)[];
					return json($data);
				}else{
					$data ['code'] = 1004;
					$data ['token'] ="";
					$data ['msg'] = '密码错误';
					$data ['data'] = (Object)[];
					return json($data);
				}
			}else {
				$data ['code'] = 1003;
				$data ['token'] ="";
				$data ['msg'] = '用户名不存在';
				$data ['data'] = (Object)[];
				return json($data);
			}
		}else{
			$data ['code'] =1002;
			$data ['token'] ="";
			$data ['msg'] = '参数错误';
			$data ['data'] = (Object)[];
			return json($data);
		}
	}
	function add(){//用户注册
		if (request()->isPost()) {//request()->isPost()
			$post=input('post.');
			$response = postRequest('https://webapi.sms.mob.com/sms/verify', array(
					'appkey' => '1dce34d5a7e9d',
					'phone' => $post['tel'],
					'zone' => '86',
					'code' => $post['code'],
			) );
			$obj = json_decode($response);//var_dump($response);//var_dump($obj);die;
			$re = $obj->{'status'};
			if ($re !== 200) {
				$data['code'] = 1002;
				$data ['msg'] = '短信验证码错误';
				$data['data'] = [];
				return json($data);
			} 
			$user=new User();
			$tel=trim($post['tel']);
			$password = trim($post['password']);
			$isuser = $user->where("tel =$tel")->find();
			if ($isuser) {
				$data['code'] = 0;
				$data['msg'] = '您已注册，请直接登录';
				$data['data'] = [];
				return json($data);
			}
			$post['password'] = Md5($post['password']);
			$post['create_time'] = time();
			$validate = $this->validate($post,'User.add');//验证信息规则
			if(true !== $validate){
				$data ['code'] = 1007;
				$data ['msg'] = $validate;
				$data ['data'] = [];
				return json($data);
			}
			$user->data($post);
			$issave=$user->allowField(true)->save();	
			//添加黑名单，新的朋友
			$group=new Group();
			$list = [
				['user_id'=>$user->user_id,'group_name'=>'未分组','sort'=>1],
			    ['user_id'=>$user->user_id,'group_name'=>'家人','sort'=>2],
			    ['user_id'=>$user->user_id,'group_name'=>'朋友','sort'=>3],
			    ['user_id'=>$user->user_id,'group_name'=>'同学','sort'=>4],
			    ['user_id'=>$user->user_id,'group_name'=>'同事','sort'=>5],
			    ['user_id'=>$user->user_id,'group_name'=>'黑名单','sort'=>6]
			];
			$group->saveAll($list);
			if ($issave){
				$data['code'] = 1001;
				$data['msg'] = '注册成功';
				$data['data'] = [];
				return json($data);
			}
			$data['code'] = 1003;
			$data['msg'] = '注册失败';
			$data['data'] = [];
			return json($data);
		}else{
			$data ['code'] =1004;
			$data ['msg'] = '参数错误';
			$data['data'] = [];
			return json($data);
		}
	}
	function editpwd(){//重置密码
		if (request()->isPost()) {
			$post=input('post.');
			$response = postRequest('https://webapi.sms.mob.com/sms/verify', array(
					'appkey' => '1dce34d5a7e9d',
					'phone' => trim($post['tel']),
					'zone' => '86',
					'code' => trim($post['code']),
			) );
			$obj = json_decode($response);
			$re = $obj->{'status'};
			if ($re !== 200) {
				$data['code'] = 0;
				$data ['msg'] = '短信验证码错误';
				return json($data);
			}
			$password = $post['password'];
			$repassword = $post['repassword'];
			if(trim($password) == trim($repassword)){
				//$user=D('User');
				$user=new User();
				$post['password'] = Md5($post['password']);
				$res=$user->allowField(['password'])->save($post,['tel' => $post['tel']]);
				if ($res!==false) {
					$data['code'] = 1001;
					$data['msg'] = '重置密码成功';
					$data['data'] = [];
					return json($data);
				}
				$data['code'] = 1003;
				$data['msg'] = '重置失败！';
				$data['data'] = [];
				return json($data);
			}else {
				$data['code'] = 1004;
				$data['msg'] = '两次密码不一致！';
				$data['data'] = [];
				return json($data);}
		}else{
			$data ['code'] =1002;
			$data ['msg'] = '参数错误';
			$data['data'] = [];
			return json($data);
		}
	}
	
	//获取周边商城
	function round(){//$lat=39.871091,$lng=116.386159$lat,$lng
// 		if (request()->isPost()) {

			$post=input('post.');//return json($post);
			$lat=$post['latitude'];//http://www.thinkphp.cn/extend/561.html 
			$lng=$post['longitude'];
			// $lat=39.871091;$lng=116.386159;
			//$business = M('business');
			$res=db('business')->select();//dump($res);die;
			//$news = M('news');
			$arr=array();//$newsall=[];
			foreach ($res as $v){
				$newsone=News::with('bimgs')->where('bid='.$v['id'] )->order('id', 'desc')
					->limit(1)->select();//
// 				$newsone=collection($newsone)->toArray();
// 				dump($newsone);die;
				$newsone=collection($newsone)->toArray();
				if (is_array($newsone) && count($newsone)>0) {
					$newsone=$newsone[0];
					//echo $newsone[0]['id'];dump($newsone);die;
					unset($newsone['id']);//dump($newsone);die;
					$v=array_merge($v,$newsone);//echo 1;dump($newsone);die;
				}else{
					$newsonemid["bid"]='';
					$newsonemid["msg"]='';
					$newsonemid["top"]='';
					$newsonemid["deleted"]='';
					$newsonemid["img"]='';
					$newsone["bimgs"]=[];
					$newsonemid["nid"]='1111111';
					$v=array_merge($v,$newsonemid);//echo 111;dump($newsonemid);die;
				}
				
				$v['distance']=round(getDistance($lat,$lng,$v['latitude'], $v['longitude']));
				if ($v['collect'] ) { $v['exist']='收藏有积分奖励哦';
				}else{ $v['exist']='';}
				$arr[]=$v;//return json($arr);
			}//return json($arr);
			$arr = my_sort($arr, 'distance', SORT_ASC,SORT_NUMERIC);
			//$page=30*($post['page'] -1);
			$result=array_slice($arr,0,30);//$result=array_slice($arr,$page,30);
			$data ['code'] = 1;
			$data ['data'] = $result;
			return json($data);
	}
	

	function bigimg(){
		if (request()->isPost()) {
			$post=input('post.');
			if (empty($post['newsimg'])) {
				$data ['code'] = 0;
				$data ['msg'] = '没有图片哦 ';
				return json($data);
			}
			$img=explode('_',$post['newsimg']);//dump($img);die;
			$imgsub=substr($img[0],0, -3);
			$imgurl['img']=$imgsub.$img[1];
			$data ['code'] = 1;
			$data ['data'] = $imgurl;
			return json($data);
		}else{
			$data ['code'] =0;
			$data ['msg'] = '参数错误';
			return json($data);
		}
		
	}
	
	
}