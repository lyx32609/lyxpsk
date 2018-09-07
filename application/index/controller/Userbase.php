<?php
namespace app\index\controller;
use think\Controller;
class Userbase extends Controller{
		public  $token;
		public  $user_id;
		public  $is_sys;
		public  $post;
		function _initialize(){//response(1,200, [],"json")->send();die;
			if (request()->isPost()) {//Post
				$this->post=input('post.');
				//request()->param();////input('get.');
				//dump($this->post);die;
			}
			if ($this->post['token']) {
				$this->token=$this->post['token'];
				$where['token']=$this->token;
				$res=db('session')->where($where)->find();
				
				if (empty($res)) {//dump($res);die;
					$data['code'] = 0;
					$data['error_msg'] = '您的账号在别的地方登录，请重新登录';
					response($data,200, [],"json")->send();die;
				}
				if ($res['expires']<time()) {
					$data['code'] = 0;
					$data['error_msg'] = '您的账号已经过期请重新登录';
					response($data,200, [],"json")->send();die;
				}
				$result=db('user')->where("tel = ".$res['session_tel'])->find();
				$this->user_id=$result['user_id'];
				$this->is_sys=$result['is_sys'];
			}else{
				$data['code'] = 0;
				$data['error_msg'] = '请您先登录...';
				response($data,200, [],"json")->send();die;
			}
		
		}
		
}