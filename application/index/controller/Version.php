<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Version extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= "";
    		
    	$list = Db::name('pxsk_version')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($version_id){
    
    	$m = Db::name('pxsk_version')->find($version_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($version_id){
    
    	if(Db::name('pxsk_version')->delete($version_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['version_id']==''){
    		$result = $this->validate($data,'Pxsk_version.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_version')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_version.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_version')
			    	->where('version_id', $data['version_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}