<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Integralnews extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and msg like binary '%$keyword%'  and newsurl like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_integral_news')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($inte_news_id){
    
    	$m = Db::name('pxsk_integral_news')->find($inte_news_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($inte_news_id){
    
    	if(Db::name('pxsk_integral_news')->delete($inte_news_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['inte_news_id']==''){
    		$result = $this->validate($data,'Pxsk_integral_news.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_integral_news')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_integral_news.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_integral_news')
			    	->where('inte_news_id', $data['inte_news_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}