<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\NewsComment as NewsCommentModel;
class Newscomment extends Userbase
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and message like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_comment')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    	$this->post['user_id'] = $this->user_id;
    	$newscomment = new NewsCommentModel($this->post);
    	// 过滤post数组中的非数据表字段数据
    	$res = $newscomment->allowField(true)->save();
    	if ($res) {
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '添加成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
    	return view('edit');
    }
    
    public function edit($comment_id){
    
    	$m = Db::name('pxsk_comment')->find($comment_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($comment_id){
    
    	if(Db::name('pxsk_comment')->delete($comment_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['comment_id']==''){
    		$result = $this->validate($data,'Pxsk_comment.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_comment')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_comment.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_comment')
			    	->where('comment_id', $data['comment_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}