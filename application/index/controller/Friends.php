<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Friends extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and title like binary '%$keyword%'  and head_img like binary '%$keyword%'  and pics_img like binary '%$keyword%'  and url like binary '%$keyword%'  and state like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_friends')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($friends_id){
    
    	$m = Db::name('pxsk_friends')->find($friends_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($friends_id){
    
    	if(Db::name('pxsk_friends')->delete($friends_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['friends_id']==''){
    		$result = $this->validate($data,'Pxsk_friends.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_friends')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_friends.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_friends')
			    	->where('friends_id', $data['friends_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}