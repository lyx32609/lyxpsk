<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Cate extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and cate_name like binary '%$keyword%'  and url like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_cate')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($cate_id){
    
    	$m = Db::name('pxsk_cate')->find($cate_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($cate_id){
    
    	if(Db::name('pxsk_cate')->delete($cate_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['cate_id']==''){
    		$result = $this->validate($data,'Pxsk_cate.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_cate')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_cate.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_cate')
			    	->where('cate_id', $data['cate_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}
	