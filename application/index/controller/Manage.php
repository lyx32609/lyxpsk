<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Pxsk_manage extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and manage_name like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_manage')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($manage_name){
    
    	$m = Db::name('pxsk_manage')->find($manage_name);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($manage_name){
    
    	if(Db::name('pxsk_manage')->delete($manage_name)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['manage_name']==''){
    		$result = $this->validate($data,'Pxsk_manage.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_manage')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_manage.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_manage')
			    	->where('manage_name', $data['manage_name'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}