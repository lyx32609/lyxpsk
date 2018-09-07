<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Trail extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and address like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_trail')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($trail_id){
    
    	$m = Db::name('pxsk_trail')->find($trail_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($trail_id){
    
    	if(Db::name('pxsk_trail')->delete($trail_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['trail_id']==''){
    		$result = $this->validate($data,'Pxsk_trail.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_trail')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_trail.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_trail')
			    	->where('trail_id', $data['trail_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}