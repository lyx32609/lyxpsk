<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Integral extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= "";
    		
    	$list = Db::name('pxsk_integral')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($user_delete){
    
    	$m = Db::name('pxsk_integral')->find($user_delete);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($user_delete){
    
    	if(Db::name('pxsk_integral')->delete($user_delete)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['user_delete']==''){
    		$result = $this->validate($data,'Pxsk_integral.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_integral')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_integral.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_integral')
			    	->where('user_delete', $data['user_delete'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}