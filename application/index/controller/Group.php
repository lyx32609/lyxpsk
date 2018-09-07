<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Group as GroupModel;
use app\index\model\GroupFriends as GroupFriendsModel;
use app\index\model\GroupFriends;
class Group extends Userbase
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and 	group_name like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_group')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
		$this->assign('page', $page);

    	return view();
    }
    
    public function grouplist(){
    	$group = new GroupModel();
        $where['user_id'] = $this->user_id;
		$where['delete'] = 0;
        $grouplist=$group->field('group_id,group_name')->where($where)
    		->order('sort asc')->select();
    	$grouplist = collection($grouplist)->toArray();//,'parent_id'=>0,'create_time'=>0,'update_time'=>0,'state'=>0,'delete'=>0
        $grouplistone = [['group_id'=>'001','group_name'=>'推荐可见'],['group_id'=>'002','group_name'=>'附近的时空可见']];
        $grouplist = arrayTostring(array_merge($grouplistone,$grouplist));
    	if ($grouplist) {
    		$data['code'] = 1001;
    		$data['data'] = $grouplist;
    		$data['msg'] = '获取成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = $grouplist;
    	$data['msg'] = '获取失败，数据为空';
    	return json($data);
    }
    public function looklist(){
    	$group = new GroupModel();
    	$where['user_id'] = $this->user_id;
    	$where['delete'] = 0;
    	$grouplist=$group->field('group_id,group_name')->where($where)
    	->order('sort asc')->select();
//     	$grouplist = collection($grouplist)->toArray();//,'parent_id'=>0,'create_time'=>0,'update_time'=>0,'state'=>0,'delete'=>0
//     	$grouplistone = [['group_id'=>'001','group_name'=>'推荐可见'],['group_id'=>'002','group_name'=>'附近的时空可见']];
//     	$grouplist = arrayTostring(array_merge($grouplistone,$grouplist));
    	if ($grouplist) {
    		$data['code'] = 1001;
    		$data['data'] = $grouplist;
    		$data['msg'] = '获取成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = $grouplist;
    	$data['msg'] = '获取失败，数据为空';
    	return json($data);
    }
    
    public function friendslist(){
    	$groupfriends = new GroupFriends();
    	$grouplist=$groupfriends
    		->where('group_id='.$this->post['group_id'])->select();
    	if ($grouplist) {
    		$data['code'] = 1001;
    		$data['data'] = $grouplist;
    		$data['msg'] = '获取成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = $grouplist;
    	$data['msg'] = '获取失败，数据为空';
    	return json($data);
    }
    
    public function add(){
    	$this->post['user_id'] = $this->user_id;
    	$group = new GroupModel($this->post);
    	// 过滤post数组中的非数据表字段数据
    	$res = $group->allowField(true)->save();
    	if ($res) {
    		//$groups = $group->where('user_id='.$this->user_id)->select();
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
    
    public function edit($group_id){
    	$group = new GroupModel();
    	$result=$group->allowField(true)
    		->save($this->post,['group_id' => $this->post['group_id']]);//更新session表信息
    	if ($result) {
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '修改成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '修改失败';
    	return json($data);
    	$m = Db::name('pxsk_group')->find($group_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function del($group_id){
    	$group = new GroupModel();
    	$where['group_id'] = $this->post['group_id'];
//     	$where['sort'] = ['>',7];
    	$where['user_id'] = $this->user_id;
    	$update['delete']=1;
    	$grouparr=$group->where($where)->find();
    	if ($grouparr['sort'] < 7) {
    		$data['code'] = 1003;
    		$data['data'] = [];
    		$data['msg'] = '不能删除系统分组';
    		return json($data);
    	}
    	$result=$group->allowField(true)
    	->save($update,$where);//更新session表信息
    	if ($result) {
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '删除成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '删除失败';
    	return json($data);
    	$m = Db::name('pxsk_group')->find($group_id);
    	$this->assign('m', $m);
    	return view();
    	if(Db::name('pxsk_group')->delete($group_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['group_id']==''){
    		$result = $this->validate($data,'Pxsk_group.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_group')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_group.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_group')
			    	->where('group_id', $data['group_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}