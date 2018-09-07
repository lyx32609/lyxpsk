<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Session extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and token like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_session')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($id){
    
    	$m = Db::name('pxsk_session')->find($id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($id){
    
    	if(Db::name('pxsk_session')->delete($id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['id']==''){
    		$result = $this->validate($data,'Pxsk_session.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_session')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_session.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_session')
			    	->where('id', $data['id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}