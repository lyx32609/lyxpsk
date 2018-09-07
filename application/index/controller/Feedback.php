<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Feedback extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and msg like binary '%$keyword%'  and state like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_feedback')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($feedback_id){
    
    	$m = Db::name('pxsk_feedback')->find($feedback_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($feedback_id){
    
    	if(Db::name('pxsk_feedback')->delete($feedback_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['feedback_id']==''){
    		$result = $this->validate($data,'Pxsk_feedback.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_feedback')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_feedback.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_feedback')
			    	->where('feedback_id', $data['feedback_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}