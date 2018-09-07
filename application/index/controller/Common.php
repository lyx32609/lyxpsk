<?php
namespace app\index\controller;
use think\Controller;
class Common extends Controller{
		public  $post;
		function _initialize(){
			if (request()->isPost()) {//Post
				$this->post=input('post.');
				//request()->param();////input('get.');
				//dump($this->post);die;
			}
		}
		
}