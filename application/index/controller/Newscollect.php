<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\NewsCollect as NewsCollectModel;

class Newscollect extends Userbase
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= "";
    		
    	$list = Db::name('pxsk_collect')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function newslist($pagesize=20){
    	$newscollect =new NewsCollectModel();
    	$list=$newscollect->where('user_id='.$this->user_id)
    		->paginate($pagesize);
    	if ($list) {
    		$data['code'] = 1001;
    		$data['data'] = $list;
    		$data['msg'] = '请求成功';
    		return json($data);
    	}
    	$data['code'] = 1001;
    	$data['data'] = $list;
    	$data['msg'] = '数据为空';
    	return json($data);
    	return view('edit');
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($collect_id){
    
    	$m = Db::name('pxsk_collect')->find($collect_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($collect_id){
    
    	if(Db::name('pxsk_collect')->delete($collect_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['collect_id']==''){
    		$result = $this->validate($data,'Pxsk_collect.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_collect')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_collect.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_collect')
			    	->where('collect_id', $data['collect_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}