<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Collect as CollectModel;
class Collect extends Userbase//Controller//
{
// 	public $user_id;
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
    
    public function add($pagesize=30){
    	$this->post['user_id'] = $this->user_id;
    	$collect = new CollectModel($this->post);
    	$where['user_id'] = $this->user_id;
    	$where['news_id'] = $this->post['news_id'];
//     	$where['delete'] = 0;
    	$iscol = db('collect')->where($where)->find();
    	if ($iscol) {
    		if ($iscol['delete'] == 0) {
    			$data['code'] = 1002;
    			$data['data'] = [];
    			$data['msg'] = '您已收藏';
    			return json($data);
    		}else{
    			$up['delete'] = 0;
    			$collect->allowField(['delete'])->save($up,$where);
    			$data['code'] = 1001;
    			$data['data'] = [];
    			$data['msg'] = '添加成功';
    			return json($data);
    		}
    	}
    	// 过滤post数组中的非数据表字段数据
    	$res = $collect->allowField(true)->save();
    	if ($res) {
//     		$list = $collect->where('user_id='.$this->user_id)
//     			->paginate($pagesize);
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '添加成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
    	return view('edit');
    }
    
    public function edit($collect_id){
    
    	$this->post['user_id'] = $this->user_id;
    	$collect = new CollectModel();
    	// 过滤post数组中的非数据表字段数据
    	$res = $collect->allowField(true)
    	->save($this->post,['collect_id' => $this->post['collect_id']]);//更新session表信息
    	//->save();
    	if ($res) {
    		$list = $collect->where('user_id='.$this->user_id)
    		->paginate($pagesize);
    		$data['code'] = 1001;
    		$data['data'] = $list;
    		$data['msg'] = '添加成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
    	$m = Db::name('pxsk_collect')->find($collect_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function  collectlist($pagesize=30,$page=1){
//     	$this->user_id=7;
    	$collect =new CollectModel();
    	$where['c.delete']=0;
    	$where['c.user_id']=$this->user_id;
    	//$where['state']=0;
    	$list=$collect//->with('news')
    		->alias('c')->field('c.collect_id,n.news_id,n.user_id,n.place,n.year,n.month,n.day,n.is_lock,n.sub_question,n.sub_content
    				,n.like_count,comment_count,u.head_img,u.nick_name')
    		->join('pxsk_news n','c.news_id = n.news_id')
    		->join('pxsk_user u','n.user_id = u.user_id')
    		->where($where)->order('c.create_time DESC')
    		->paginate($pagesize)->toArray();//dump($list);die;
    	if ($list) {
    		$data['code'] = 1001;
            $data['msg'] = '请求成功';
            $data['page'] = $page;
            $data['total'] =  $list['total'];
    		$data['data'] = $list['data'];//['data']
    		return json($data);
    	}
    	$data['code'] = 1001;
    	$data['data'] = $list;
    	$data['msg'] = '数据为空';
    	return json($data);
    	return view('edit');
    }
    
    public function delete(){
    	$collect = new CollectModel();
    	$update['delete']=1;
    	// 过滤post数组中的非数据表字段数据
    	$res = $collect->allowField(true)
    	->save($update,['collect_id' => $this->post['collect_id']]);//更新session表信息
    	//->save();
    	if ($res) {
//     		$list = $collect->where('user_id='.$this->user_id)
//     		->paginate($pagesize);
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '请求成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
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