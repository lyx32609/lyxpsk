<?php 
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Session;
use app\index\model\UserSys as UserSysModel;
use app\index\model\User;
use app\index\model\GroupFriends;
use app\index\model\Group;
class Usersys extends Userbase//Controller//
{
    public function add(){
    	//friends_user_id
//     	$this->post['type']= 2;
    	$this->post['friends_user_id']= $this->user_id;
//     	$this->post['user_id']= $this->user_id;
    	$usersys = new UserSysModel($this->post);
    	// 过滤post数组中的非数据表字段数据
    	$res = $usersys->allowField(true)->save();
    	if ($res) {
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '添加成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
    }
    //点击添加用户为好友   查到此人之后，我会点击这个人进行添加
    public function useradd(){
    	$this->post['type']= 2;//通过搜索手机号添加
    	$this->post['friends_user_id']= $this->user_id;
    	//     	$this->post['user_id']= $this->user_id;
//     	if ($this->post['user_id'] == $this->user_id) {
//     		$data ['code'] = 1010;
//     		$data ['msg'] = '不能添加自己为好友';
//     		$data ['data'] = [];
//     		return json($data);
//     	}
    	$usersys = new UserSysModel($this->post);
    	// 过滤post数组中的非数据表字段数据
		$groupfriend = new GroupFriends();
		$iswhere['user_id'] = $this->user_id;
		$iswhere['friends_user_id'] = $this->post['user_id'];
		$iswhere['delete'] = 0;
    	$isfriends = $groupfriend->where($iswhere)->find();
    	if ($isfriends) {
    		$data ['code'] = 1005;
    		$data ['msg'] = '已经是好友，不能再添加';
    		$data ['data'] = [];
    		return json($data);
    	}
    	$res = $usersys->allowField(true)->save();
    	if ($res) {
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '添加成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
    }
    //获取用户所有系统消息
    public function userlst($pagesize=30){
    	$usersys = new UserSysModel();
    	$where['delete'] = 0;
    	$where['is_handle'] = 0;
    	$where['user_id'] = $this->user_id;
    	$userlst=$usersys::with('imgs')->field('user_sys_id,friends_user_id,user_id')
    		->where($where)->paginate($pagesize)->toArray();
    	if ($userlst) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['data'] = $userlst['data'];
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '数据为空';
    	$data ['data'] = [];
    	return json($data);
    }
    
    //获取用户所有系统消息历史
    public function oldlst($pagesize=30){
    	$usersys = new UserSysModel();
    	$where['delete'] = 0;
    	$where['user_id'] = $this->user_id;
    	$userlst=$usersys::with('imgs')->field('user_sys_id,friends_user_id,user_id')
    	->where($where)->paginate($pagesize)->toArray();
    	if ($userlst) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['data'] = $userlst['data'];
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '数据为空';
    	$data ['data'] = [];
    	return json($data);
    }
    
    // //用户查看单个系统消息
    // public function userone($pagesize=30){
    // 	$param = $this->post;
    // 	$usersys = new UserSysModel();
    // 	$where['user_sys_id'] = $param['user_sys_id'];
    // 	$where['delete'] = 0;
    // 	$where['user_id'] = $this->user_id;
    // 	$up['is_look'] = 1;
    // 	$userlst=$usersys::with('users')->where($where)
    // 	->paginate($pagesize)->toArray();
    // 	if ($userlst) {
    // 		$usersys->allowField(true)->save($up,['user_sys_id' => $param['user_sys_id']]);
    // 		$data ['code'] = 1001;
    // 		$data ['msg'] = '请求成功';
    // 		$data ['data'] = $userlst'data';
    // 		return json($data);
    // 	}
    // 	$data ['code'] = 1003;
    // 	$data ['msg'] = '数据为空';
    // 	$data ['data'] = [];
    // 	return json($data);
    // }
    //用户查看单个系统消息
    public function userone($pagesize=30){
        $param = $this->post;
        $usersys = new UserSysModel();
        $where['user_sys_id'] = $param['user_sys_id'];
        $where['delete'] = 0;
        $where['user_id'] = $this->user_id;
        $up['is_look'] = 1;
        $userlst=$usersys::with('users')->where($where)->find()->toArray();
//      ->paginate($pagesize)->toArray();
        if ($userlst) {
            $usersys->allowField(true)->save($up,['user_sys_id' => $param['user_sys_id']]);
            $data ['code'] = 1001;
            $data ['msg'] = '请求成功';
            $data ['data'] = $userlst;//['data'];
            return json($data);
        }
        $data ['code'] = 1003;
        $data ['msg'] = '数据为空';
        $data ['data'] = [];
        return json($data);
    }
    
    //用户点击确认或者取消返回
    public function Button(){
    	if (isset($this->post['state'])) {
    		$usersys = new UserSysModel();
    		$usersysone = $usersys->where('user_sys_id ='.$this->post['user_sys_id'])->find();
    		$up['is_handle'] = 1;
    		$usersys->allowField(true)->save($up,['user_sys_id' => $this->post['user_sys_id']]);
    		$savedata['friends_user_id'] = $usersysone['friends_user_id'];
    		if ($this->post['state'] == 1) {//确认同意
//     			$groupfriend = new Groupfriends();
				$where['state'] = 0;
				$where['sort'] = 5;
                $where['delete'] = 0;
				$where['user_id'] = $this->user_id;
				$group = new Group();
				$groupone = $group->where($where)->find();
				$where['user_id'] = $usersysone['friends_user_id'];
				$grouptwo = $group->where($where)->find();//return json($groupone);
				$up['delete'] = 0;
                //->order('sort asc')
				if ($groupone && $grouptwo) {//有分组
					$savedata['user_id'] = $this->user_id;
					$savedata['group_id'] = $groupone['group_id'];
					$groupfriend = new GroupFriends();
					$list = [
						['user_id'=>$this->user_id,'friends_user_id'=>$usersysone['friends_user_id'],'group_id'=>$groupone['group_id']],
						['user_id'=>$usersysone['friends_user_id'],'friends_user_id'=>$this->user_id,'group_id'=>$grouptwo['group_id']],
					];//return json($list);
					if ($list[0] == $list[1]) {
						$iswhere = $list[0];
						$isfriends = $groupfriend->where($iswhere)->find();
						if ($isfriends) {
							$iswhere['friends_id'] = $isfriends['friends_id'];
							$savenum = $groupfriend->allowField(true)->save($up,$iswhere);
// 							$savenum = $groupfriend->allowField(true)->save($list[1]);
						}else{
							$savenum = $groupfriend->allowField(true)->save($list[1]);
						}
						if ($savenum) {
							$data ['code'] = 1001;
							$data ['msg'] = '添加成功';
							$data ['data'] = [];
							return json($data);
						}else{
							$data ['code'] = 1002;
							$data ['msg'] = '添加失败';
							$data ['data'] = [];
							return json($data);
						}
					}
					$iswhere = $list[0];//自己
// 					$iswhere['delete'] = 0;
					$isfriends = $groupfriend->where($iswhere)->find();
					$istwowhere = $list[1];//好友
// 					$istwowhere['delete'] = 0;
					$istwofriends = $groupfriend->where($istwowhere)->find();
					if ($isfriends && $istwofriends) {
						// if ($isfriends['delete'] == 0) {
						// 	$data ['code'] = 1005;
						// 	$data ['msg'] = '已经是好友';
						// 	$data ['data'] = [];
						// 	return json($data);
						// }
                        $upwhere['friends_id'] = ['in',[$isfriends['friends_id'],$istwofriends['friends_id']]];
						$savenum = $groupfriend->allowField(['delete'])->save($up,$upwhere);
					}elseif($isfriends) {
                        $iswhere['friends_id'] = $isfriends['friends_id'];
                        $savenum = $groupfriend->allowField(true)->save($list[1]);
                        $groupfriend->allowField(true)->save($up,$iswhere);
                    }elseif($istwofriends) {
                        $iswhere['friends_id'] = $istwofriends['friends_id'];
                        $savenum = $groupfriend->allowField(true)->save($list[0]);
                        $groupfriend->allowField(true)->save($up,$iswhere);
                    }else{
                        $savenum = $groupfriend->allowField(true)->saveAll($list);
                    }
// 					$user->saveAll($list);
					// 过滤post数组中的非数据表字段数据
// 					$savenum = $groupfriend->allowField(true)->saveAll($list);
				}
    //             else{//没分组
				// 	$list = [
				// 		['user_id'=>$this->user_id,'group_name'=>'新的朋友','sort'=>1],
				// 		['user_id'=>$this->user_id,'group_name'=>'黑名单','sort'=>999]
				// 	];
				// 	$group->saveAll($list);
				// 	$groupone = $group->where($where)->order('sort asc')->find();
				// 	$savedata['user_id'] = $this->user_id;
				// 	$savedata['group_id'] = $groupone['group_id'];
				// 	$groupfriend = new GroupFriends($savedata);
				// 	// 过滤post数组中的非数据表字段数据
				// 	$savenum = $groupfriend->allowField(true)->save();
				// }
				if ($savenum) {
					$data ['code'] = 1001;
		    		$data ['msg'] = '添加成功';
		    		$data ['data'] = [];
		    		return json($data);
		    	}
		    	$data ['code'] = 1003;
		    	$data ['msg'] = '添加失败';
		    	$data ['data'] = [];
		    	return json($data);
    			
    		}else{//拒绝
//     			$refuse['refuse'] = 1;
    			$refuse['user_id'] = $usersysone['friends_user_id'];
    			$refuse['friends_user_id'] = $this->user_id;
    			$refuse['state'] = 2;
    			$usersys = new UserSysModel($refuse);
    			// 过滤post数组中的非数据表字段数据
    			$savenum = $usersys->allowField(true)->save();
    			if ($savenum) {
    				$data ['code'] = 1001;
    				$data ['msg'] = '拒绝成功';
    				$data ['data'] = [];
    				return json($data);
    			}
    			$data ['code'] = 1003;
    			$data ['msg'] = '';
    			$data ['data'] = [];
    			return json($data);
    		}
    	}
    	$userlst=$usersys::with('users')->where(['user_id' => $this->user_id])
    	->paginate($pagesize)->toArray();
    	if ($userlst) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['data'] = $userlst['data'];
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '数据为空';
    	$data ['data'] = [];
    	return json($data);
    }
    public function edit(){
    	$userid=$this->user_id;//$token=43;//echo ROOT_PATH;die;
        //$userid=1;//echo 1;die;
        //$this->post['user_name']=1211222;
    	//$savePath =  './uploads/user/'.$token.'/';// 设置附件上传目录
    	$savePath=ROOT_PATH.'public/uploads/user/'.$userid.'/head';
    	if (!file_exists($savePath)||!is_dir($savePath)){
    		mkdir($savePath,0777,true);
    	}
    	if (isset($this->post['img'])) {//头像保存更新
    		$filename =date("ymdHis");
    		$file=$savePath."/".$filename.".png";
    		$myfile='/uploads/user'."/".$userid.'/head/'.$filename.".png";
    		$string =base64_decode($this->post['img']);
    		file_put_contents($file,$string);
    		$image = \think\Image::open($file);
    		$width = $image->width(); // 返回图片的宽度
    		$height = $image->height();// 按照原图的比例生成缩略图并保存为thumb.jpg
    		$image->thumb($width, $height)->save($savePath.'/thumb_'.$filename.'.jpg');
    		$endfile="/uploads/user/".$userid."/head/thumb_".$filename.".jpg";//图片入库路径
    		$this->post['head_img']=$myfile;
    		$this->post['thumb_img']=$endfile;
    	}
    	$date = $this->post;//dump($token);die;
    	$user=new UserSysModel();
    	$issave=$user->allowField(true)->save($date,['user_id' => $userid]);
    	$userone =$user->get(['user_id' => $userid]);
    	if($issave!==false){
    		$end['code'] = 1001;
    		$end['msg'] = '更新成功！ ';
    		$end['data'] = $userone;
    		return json($end);
    	}else{
    		//$this->error("注册失败",U("Home/user/register"),3);
    		$end['code'] = 1003;
    		$end['msg'] = '更新失败！';
    		$end['data'] = $userone;
    		return json($end);
    	}
    	
    	
    	$m = Db::name('pxsk_user')->find($tel);
    	$this->assign('m', $m);
    	return view();
    }
    
    
    public function delete($tel){
    
    	if(Db::name('pxsk_user')->delete($tel)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    //根据手机号查询用户添加好友
    public function findone(){
		$user = new User();
		$this->post = array_filter($this->post);
    	$where['delete'] = 0;
    	if (isset($this->post['tel'])) $where['tel'] = $this->post['tel'];
    	if (isset($this->post['nick_name'])) $where['nick_name'] = ['like','%'.$this->post['nick_name'].'%'];
    	$userone = $user->field('user_id,tel,head_img,nick_name,signature')
    		->where($where)->find();//dump($userone);die;
    	if ($userone) {
//             if ($userone['user_id'] == $this->user_id) {
//                 $data ['code'] = 1003;
//                 $data ['msg'] = '不能添加自己为好友';
//                 $data ['data'] = $userone;
//                 return json($data);
//             }
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['data'] = $userone;
    		return json($data);
    	}
    	$data ['code'] = 1002;
    	$data ['msg'] = '数据为空';
    	$data ['data'] = $userone;
    	return json($data);
    }
    
    public function search($pagesize=30){
    	$user = new User();
    	$this->post = array_filter($this->post);
    	$where['delete'] = 0;
    	if (isset($this->post['tel'])) $where['tel'] = $this->post['tel'];
    	if (isset($this->post['nick_name'])) $where['nick_name'] = ['like','%'.$this->post['nick_name'].'%'];
    	$userone = $user->field('user_id,tel,head_img,nick_name,signature')
    	->where($where)->paginate($pagesize);//dump($userone);die;
//     	return json($user->getLastsql());
    	if ($userone) {
            // if ($userone['user_id'] = $this->user_id) {
            //     $data ['code'] = 1003;
            //     $data ['msg'] = '不能添加自己为好友';
            //     $data ['data'] = $userone;
            //     return json($data);
            // }
    		$userone = $userone->toArray();
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['data'] = $userone['data'];
    		return json($data);
    	}
    	$data ['code'] = 1002;
    	$data ['msg'] = '数据为空';
    	$data ['data'] = $userone;
    	return json($data);
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['tel']==''){
    		$result = $this->validate($data,'Pxsk_user.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_user')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_user.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_user')
			    	->where('tel', $data['tel'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}