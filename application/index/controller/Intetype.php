<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Intetype extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and typename like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_inte_type')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($intetype_id){
    
    	$m = Db::name('pxsk_inte_type')->find($intetype_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($intetype_id){
    
    	if(Db::name('pxsk_inte_type')->delete($intetype_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['intetype_id']==''){
    		$result = $this->validate($data,'Pxsk_inte_type.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_inte_type')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_inte_type.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_inte_type')
			    	->where('intetype_id', $data['intetype_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}