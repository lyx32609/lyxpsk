<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Manager extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and head_img like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_manager')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($tel){
    
    	$m = Db::name('pxsk_manager')->find($tel);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($tel){
    
    	if(Db::name('pxsk_manager')->delete($tel)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['tel']==''){
    		$result = $this->validate($data,'Pxsk_manager.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_manager')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_manager.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_manager')
			    	->where('tel', $data['tel'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}