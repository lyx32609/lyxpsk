<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;

class Newsimg extends Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and thumb_img like binary '%$keyword%'  and news_img like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_news_img')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    
    	return view('edit');
    }
    
    public function edit($img_id){
    
    	$m = Db::name('pxsk_news_img')->find($img_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($img_id){
    
    	if(Db::name('pxsk_news_img')->delete($img_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['img_id']==''){
    		$result = $this->validate($data,'Pxsk_news_img.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_news_img')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_news_img.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_news_img')
			    	->where('img_id', $data['img_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}