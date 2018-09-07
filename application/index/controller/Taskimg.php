<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Taskimg extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and thumb_img like binary '%$keyword%'  and task_img like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_task_img')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($timg_id){
    
    	$m = Db::name('pxsk_task_img')->find($timg_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($timg_id){
    
    	if(Db::name('pxsk_task_img')->delete($timg_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['timg_id']==''){
    		$result = $this->validate($data,'Pxsk_task_img.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_task_img')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_task_img.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_task_img')
			    	->where('timg_id', $data['timg_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}