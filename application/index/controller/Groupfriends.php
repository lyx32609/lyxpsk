<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\GroupFriends as GroupFriendsModel;
use app\index\model\UserSys;
class Groupfriends extends Userbase//Controller//Userbase
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and nick_name like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_group_friends')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function friendslist(){
    	$groupfriends = new GroupFriendsModel();
    	//return json($this->user_id);
    	$where['delete'] = 0;
    	$friendslist = $groupfriends->with('users,groups')
    		->where('user_id='.$this->user_id)
    		->where($where)->select();
    	if ($friendslist) {
    		$data['code'] = 1001;
    		$data['data'] = $friendslist;
    		$data['msg'] = '请求成功';
    		return json($data);
    	}
    	$data['code'] = 1003;
    	$data['data'] = $friendslist;
    	$data['msg'] = '数据为空';
    	return json($data);
    	return view('edit');
    }
    
    public function groupone(){
    	$groupfriends = new GroupFriendsModel();
    	//return json($this->user_id);
    	$param = request()->param();
    	$where['group_id'] = $param['group_id'];
    	$where['delete'] = 0;//var_dump($param);die;
    	$friendslist = $groupfriends->with('users')
    	->where('user_id='.$this->user_id)
    	->where($where)
    	->order('convert(nick_name using gbk) collate gbk_chinese_ci asc')
    	->select();//var_dump($friendslist);die;
    	if ($friendslist) {
    		$data['code'] = 1001;
    		$data['data'] = $friendslist;
    		$data['msg'] = '请求成功';
    		return json($data);
    	}
    	$data['code'] = 1003;
    	$data['data'] = $friendslist;
    	$data['msg'] = '数据为空';
    	return json($data);
    	return view('edit');
    }
    
    public function friendsall($pagesize=30,$page=1){
    	$groupfriends = new GroupFriendsModel();
    	$where['delete'] = 0;
    	//return json($this->user_id);
    	$friendslist = $groupfriends->with('users')
    	->where('user_id='.$this->user_id)
    	->where($where)
    	->order('convert(nick_name using gbk) collate gbk_chinese_ci asc')
    	->paginate($pagesize)->toArray();//dump($list);die;
    	if ($friendslist) {
    		$data['code'] = 1001;
            $data['msg'] = '请求成功';
            $data['page'] = $page;
            $data['total'] = $friendslist['total'];
    		$data['data'] = $friendslist['data'];
    		return json($data);
    	}
    	$data['code'] = 1003;
        $data['msg'] = '数据为空';
        $data['page'] = 1;
        $data['total'] = 0;
    	$data['data'] = [];
    	return json($data);
    	return view('edit');
    }
    //获取用户单个组和组里面的所有用户以及一个动态
    public function friendsnews(){
    	$param = $this->post;//return json($this->user_id);
    	$groupfriends = new GroupFriendsModel();
    	//return json($this->user_id);
    	$friendslist = $groupfriends->with('users,chats')
    	->where('user_id='.$this->user_id)
    	->where('group_id = '.$param['group_id'])
    	->order('convert(nick_name using gbk) collate gbk_chinese_ci asc')
    	->select();
    	if ($friendslist) {
    		$data['code'] = 1001;
    		$data['data'] = $friendslist;
    		$data['msg'] = '请求成功';
    		return json($data);
    	}
    	$data['code'] = 1003;
    	$data['data'] = $friendslist;
    	$data['msg'] = '数据为空';
    	return json($data);
    	return view('edit');
    }
    //添加好友
	public function Midadd(){
		
    	$usersys = new UserSys($this->post);
    	$res = $usersys->allowField(true)->save();
    	if ($res) {
    		//$groups = $group->where('user_id='.$this->user_id)->select();
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '请求成功，等待对方确认';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
    	return view('edit');
    }
    
    public  function detail(){
    	if (isset($this->post['friends_id'])) {
    		$groupfriends = new GroupFriendsModel();
    		$where['friends_id'] = $this->post['friends_id'];
    		$where['user_id'] = $this->user_id;
    		$where['delete'] = 0;
    		$userone = $groupfriends
    			->field('friends_id,user_id,group_id,nick_name,friends_user_id')
    			->where($where)->find();
    		if ($userone) {
    			$data ['code'] = 1001;
    			$data ['msg'] = '请求成功';
    			$data ['data'] = $userone;
    			return json($data);
    		}
    		
    	}
    	$data ['code'] = 1002;
    	$data ['msg'] = '请求失败';
    	$data ['data'] = [];
    	return json($data);
    }
    
    public function edit(){
    	$groupfriends = new GroupFriendsModel();
//     	$this->post['friends_id']=1;
//     	$this->post['nickname']=111;
    	$result=$groupfriends->allowField(true)
    	->save($this->post,['friends_id' => $this->post['friends_id']]);//更新session表信息
    	if ($result) {
    		$data['code'] = 1001;
    		$data['data'] = $this->post['nick_name'];
    		$data['msg'] = '修改成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '修改失败';
    	return json($data);
    	$m = Db::name('pxsk_group_friends')->find($friends_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function group(){
    	$groupfriends = new GroupFriendsModel();
//     	$this->post['group_id']=1;
//     	$this->post['nickname']=111;
    	$result=$groupfriends->allowField(true)
    	->save($this->post,['friends_id' => $this->post['friends_id']]);//更新session表信息
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
    	$m = Db::name('pxsk_group_friends')->find($friends_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete(){
    	$groupfriends = new GroupFriendsModel();
    	if ( isset($this->post['friends_id'])) {
    		$del['delete'] = 1;
    		$result=$groupfriends->allowField(['delete'])
    		->save($del,['friends_id' => $this->post['friends_id']]);
    		$friends = $groupfriends::get($this->post['friends_id']);
    		$where['user_id'] = $friends['friends_user_id'];
    		$where['friends_user_id'] = $friends['user_id'];
    		$isfriends = $groupfriends->where($where)->find();
    		if ($isfriends) {
    			// $isfriends = collection($isfriends)->toArray();
    			$groupfriends->allowField(['delete'])
    			->save($del,['friends_id' => $isfriends['friends_id']]);
    		}
    		if ($result) {
    			$data['code'] = 1001;
    			$data['data'] = [];
    			$data['msg'] = '删除成功';
    			return json($data);
    		}
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '删除失败';
    	return json($data);
    }
    
    public function save(){
    	$data=input('post.');
    	if($data['friends_id']==''){
    		$result = $this->validate($data,'Pxsk_group_friends.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_group_friends')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_group_friends.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_group_friends')
			    	->where('friends_id', $data['friends_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}