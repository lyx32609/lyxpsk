<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class District extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and district_name like binary '%$keyword%'  and zipcode like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_district')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($id){
    
    	$m = Db::name('pxsk_district')->find($id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($id){
    
    	if(Db::name('pxsk_district')->delete($id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['id']==''){
    		$result = $this->validate($data,'Pxsk_district.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_district')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_district.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_district')
			    	->where('id', $data['id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}
	