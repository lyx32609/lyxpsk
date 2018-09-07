<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Sort extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and 	
sort_name like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_sort')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($sort_id){
    
    	$m = Db::name('pxsk_sort')->find($sort_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($sort_id){
    
    	if(Db::name('pxsk_sort')->delete($sort_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['sort_id']==''){
    		$result = $this->validate($data,'Pxsk_sort.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_sort')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_sort.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_sort')
			    	->where('sort_id', $data['sort_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}