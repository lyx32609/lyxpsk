<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\News as NewsModel;
use app\index\model\NewsImg as NewsImgModel;
use app\index\model\GroupFriends as GroupFriendsModel;
use app\index\model\Collect as CollectModel;
use app\index\model\LockImg;
class News extends Userbase//Controller//Userbase//
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and msg like binary '%$keyword%'  and longitude like binary '%$keyword%'  and question like binary '%$keyword%'  and answer like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_news')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	$this->assign('list', $list);
		$this->assign('page', $page);

    	return view();
    }
    

    public function userlst($pagesize=30,$page=1){//获取用户所有动态
    	$news = new NewsModel();//$user = User::get(['name' => 'thinkphp']);
    	$userlst=$news::with('newsimgs,users')->where(['user_id' => $this->user_id])
    		->field('news_id,user_id,place,year,month,day,is_lock,sub_question,sub_content,like_count,comment_count')
    		->order('news_id desc')->paginate($pagesize)->toArray();
    	if ($userlst) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['total'] = $userlst['total'];
    		$data ['page'] = $page;
    		$data ['data'] = $userlst['data'];
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '数据为空';
    	$data ['total'] = 0;
    	$data ['page'] = 1;
    	$data ['data'] = [];
    	return json($data);
    }
    
    public function restrict($pagesize=30){//查看用户的时空查看历史动态只查看拥权限的
    	$news = new NewsModel();
    	$userid = $this->user_id;
    	$friends= new GroupFriendsModel();
    	$fwhere['user_id'] = $this->post['user_id'];
    	$fwhere['friends_user_id'] = $userid;
    	$fwhere['delete'] = $userid;
    	$isfriends = $friends->where($fwhere)->find();
    	if ($isfriends) {
    		$where['user_id']=$this->post['user_id'];
    		$where['delete'] = 0;//dump($where);die;
    		$userlst=$news::with('users')->field('news_id,user_id,place,year,month,day,is_lock,question,content')
    		->where(function($query)use($userid){
    			$query//->where('allow_look','')
    			->where('FIND_IN_SET("'.$userid.'",allow_look)');
    		})
    		->where($where)
    		->order('create_time desc')
    		->paginate($pagesize)->toArray();
    	}else{
    		$where['user_id']=$this->post['user_id'];
//     		$where['is_recommend'] = 1;
    		$where['delete'] = 0;//dump($where);die;
    		$userlst=$news::with('users')->field('news_id,user_id,place,year,month,day,is_lock,question,content')
    		->where(function($query)use($userid){
    			$query->where('is_recommend',1)
    			->where('no_friends',1);
    		})
    		
    		->where($where)
    		->order('create_time desc')
    		->paginate($pagesize)->toArray();
    	}
    	if (!empty($userlst)) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['total'] = $userlst['total'];
    		$data ['page'] = $page;
    		$data ['data'] = $userlst['data'];//arrayTostring();
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '数据为空';
    	$data ['total'] = 0;
    	$data ['page'] = 1;
    	$data ['data'] = [];
    	return json($data);
    }
    
    public function friendslist($pagesize=30,$page=1){//获取好友所有动态
    	$news = new NewsModel();//$user = User::get(['name' => 'thinkphp']);
    	$userid=$this->user_id;
    	$friends=GroupFriendsModel::all(['user_id' => $userid]);//return json($userids);
    	$userids = array_column($friends, 'friends_user_id');
        $userids[] = $userid;//return json($userids);die;
        //$useridstr = implode(',', $userids);//echo $useridstr;die;
    	$where['user_id']=['in',$userids];
        $where['delete']=['eq',0];//dump($where);die;
    	$userlst=$news::with('users')
    		->field('news_id,user_id,place,year,month,day,is_lock,sub_question,sub_content,like_count,comment_count')
            ->where(function($query)use($userid){
                //where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
                $query->where('FIND_IN_SET("'.$userid.'",allow_look)');
//                 ->where('FIND_IN_SET("'.$userid.'",allow_look)');->whereOr('user_id = '.$userid)
            })
            ->where($where)
            //->where('user_id in "'.$useridstr.'" and delete eq 0')
            ->order('create_time desc')
    		->paginate($pagesize)->toArray();
//     	->where(function ($query) use($userid){
//     		$query->where('banner_id','=',$userid);
//     	})
//     	Db::query("select * from think_user where id=? AND status=?",[8,1]);
// 		$list=Db::query("select pn.msg,pu.user_name,pnli.lock_img from 
// 				pxsk_news pn join pxsk_user pu on pn.user_id= pu.user_id 
// 				join pxsk_news_lock_img pnli on pnli.news_id=pn.news_id 
// 				where pn.user_id=$userid AND FIND_IN_SET($userid,pn.allow_look)");
		//$list=Db::query("select * from pxsk_news where user_id in ($userid) AND FIND_IN_SET(7,allow_look)");
		//$list=Db::query("select * from pxsk_news");//,[$userid,$userid]
    	if (!empty($userlst)) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['total'] = $userlst['total'];
    		$data ['page'] = $page;
    		$data ['data'] = $userlst['data'];//arrayTostring();
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '数据为空';
    	$data ['total'] = 0;
    	$data ['page'] = 1;
    	$data ['data'] = [];
    	return json($data);
    }
    
     public function Isself(){//判断是否是自己发布的动态
        $news = new NewsModel();//$user = User::get(['name' => 'thinkphp']);
        $where['user_id'] = $this->user_id;
        $where['news_id'] = $this->post['news_id'];
        $userlst=$news->where($where)->find();
        if ($userlst) {
            $data ['code'] = 1001;
            $data ['msg'] = '有权限';
            return json($data);
        }
        $data ['code'] = 1003;
        $data ['msg'] = '没有权限';
        return json($data);
    }

    public function uplook(){//获取用户所有动态
//     	$end['code'] = 1001;$end['msg'] = json_encode($this->post);$end['data'] = 1001;
//     	return json($end);
        $news = new NewsModel();//$user = User::get(['name' => 'thinkphp']);
        $iswhere['news_id'] = $this->post['news_id'];
        $iswhere['user_id'] = $this->user_id;
//         $groupids = $this->post['group_id'];
        $rtrimstr = ltrim($this->post['group_id'], '[');
        $this->post['group_id'] = array_filter(explode(',',rtrim($rtrimstr, ']')));
        if (in_array('001', $this->post['group_id'])) {//推荐
        	$saveparam['is_recommend'] = 1;
        	foreach ($this->post['group_id'] as $k=>$v){
        		if ($v === '001'){
        			array_splice($this->post['group_id'],$k,1);
        		}
        	}
        }else{
            $saveparam['is_recommend'] = 0;
        }
        if (in_array('002', $this->post['group_id'])) {//推荐
        	$saveparam['no_friends'] = 1;
        	foreach ($this->post['group_id'] as $k=>$v){
        		if ($v === '002'){
        			array_splice($this->post['group_id'],$k,1);
        		}
        	}
        }else{
            $saveparam['no_friends'] = 0;
        }//return json($this->post);
        $groupids = $this->post['group_id'];
        if ($groupids) {
            $where['group_id']=['in', $groupids];//dump($where);die;
            $where['state'] = 0;
            $where['delete'] = 0;//dump($where);die;
            $groupfriendss = new GroupFriendsModel();
            $groupfriendarr = $groupfriendss->where($where)//->where($where)
                    ->select();//dump($groupfriendarr);die;
            $userids = array_column($groupfriendarr,"friends_user_id");//dump($userids);die;
            $userids[] = $this->user_id;

            $userids = array_flip(array_flip($userids));

            $useridstr = implode(",", $userids);
            $saveparam['allow_look'] = $useridstr;//dump($this->post);die;
        } else{
            $saveparam['allow_look'] = '';
        }
        // $userlst=$news->where($where)->find();
        $uplook=$news->allowField(['is_recommend','no_friends','allow_look'])->save($saveparam,$iswhere);
        if ($uplook) {
            $data ['code'] = 1001;
            $data ['msg'] = '请求成功';
            return json($data);
        }
        $data ['code'] = 1003;
        $data ['msg'] = '修改失败';
        return json($data);
    }

    // public function userlst($pagesize=30){//获取用户所有动态
    //     $news = new NewsModel();//$user = User::get(['name' => 'thinkphp']);
    //     $userlst=$news::with('newsimgs,users')->where(['user_id' => $this->user_id])->paginate($pagesize)->toArray();
    //     if ($userlst) {
    //         $data ['code'] = 1001;
    //         $data ['msg'] = '请求成功';
    //         $data ['data'] = $userlst['data'];
    //         return json($data);
    //     }
    //     $data ['code'] = 1003;
    //     $data ['msg'] = '数据为空';
    //     $data ['data'] = [];
    //     return json($data);
    // }
    
    public function recommendlist($pagesize=30,$page=1){//获取好友所有动态
        $news = new NewsModel();//$user = User::get(['name' => 'thinkphp']);
        $userid=$this->user_id;
        $fwhere['user_id'] = $userid;
        $fwhere['delete'] = 0;
        $friends=GroupFriendsModel::all($fwhere);//return json($userids);
        $userids = array_column($friends, 'friends_user_id');

        $tempArr = array_flip($userids);  
        unset($tempArr[$this->user_id]);  
        $userids = array_flip($tempArr);
        
        // $userids[] = $userid;//return json($userids);die;
        //$useridstr = implode(',', $userids);//echo $useridstr;die;
        $where['is_recommend'] = 1;
        $where['top'] = 0;
//         $where['user_id'] = ['not in',$userids];
//         $query->where('user_id','exp',"find_in_set($userid,allow_look)");
        $where['delete']=0;//dump($where);die;
        $userlst=$news::with('users')
        ->field('news_id,user_id,place,year,month,day,is_lock,sub_question,sub_content,latitude,longitude,like_count,comment_count')
//             ->where(function($query)use($userid){
//                 //where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
//                 $query->where('allow_look','')
//                 ->whereOr('FIND_IN_SET("'.$userid.'",allow_look)');
//             })
// 	        ->where(function($query)use($userid){
// 	        	//where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
// 	        	$query//->where('allow_look','')
// 	        	->where('user_id','not in',$userids);
// 	        })
        ->where($where)//'FIND_IN_SET("'.$userid.'",allow_look)'
         ->where(function($query)use($userid,$userids){
			//where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
			$query->where("find_in_set($userid,allow_look)")
			->whereOr('user_id','not in',$userids);
		})->order('create_time desc')->paginate($pagesize)->toArray();//echo $news->getLastSql();die;
        $topwhere['top'] = 1;
        $topwhere['delete'] = 0;
        $topwhere['is_recommend'] = 1;
        $userone=$news::with('users')
        ->field('news_id,user_id,place,year,month,day,is_lock,sub_question,sub_content,latitude,longitude,like_count,comment_count')
        ->where($topwhere)->order('create_time desc')->find();
        if ($userone && $page == 1) {
        	array_unshift($userlst['data'],$userone);
        }
        if (!empty($userlst)) {
            $data ['code'] = 1001;
            $data ['msg'] = '请求成功';
    		$data ['total'] = $userlst['total'];
    		$data ['page'] = $page;
            $data ['data'] = $userlst['data'];//arrayTostring();
            return json($data);
        }
        $data ['code'] = 1003;
        $data ['msg'] = '数据为空';
    	$data ['total'] = 0;
    	$data ['page'] = 1;
        $data ['data'] = [];
        return json($data);
    }
    //获取动态锁的信息，多张图片
    function locks(){
        $news = new NewsModel();
        if (isset($this->post['news_id'])) {
            $where['news_id'] = $this->post['news_id'];
            $where['delete'] = 0;
            $newsarr = $news->field('news_id,question')
                ->with('lockimgs')->where($where)->find();
            // if (empty($newsarr)) {
            //     $data ['code'] = 1003;
            //     $data ['msg'] = '数据为空';
            //     $data ['data'] = [];
            //     return json($data);
            // }
            $data ['code'] = 1001;
            $data ['msg'] = '请求成功';
            $data ['data'] = $newsarr;
            return json($data);
        }
        $data ['code'] = 1002;
        $data ['msg'] = '参数错误';
    	$data ['total'] = 0;
    	$data ['page'] = 1;
        $data ['data'] = $newsarr;
        return json($data);
    }
    //附近的时空
    public function round11($pagesize=30){
    	//经纬度
    	$lng=$this->post['longitude'];
    	$lat=$this->post['latitude'];
    	$userid = $this->user_id;
    	$friends=GroupFriendsModel::all(['user_id' => $userid]);//return json($userids);
    	$userids = array_column($friends, 'friends_user_id');
    	//$useridstr = implode(',', $userids);//echo $useridstr;die;
//     	$where['user_id']=['in',$userids];
    	$news = new NewsModel();
    	$where['no_friends'] = 1;
    	$where['state'] = 0;
    	$where['delete'] = 0;
    	$newsall = $news->with('users')
    		->field('news_id,user_id,place,year,month,day,is_lock,question,content,latitude,longitude,like_count,comment_count')
	    	->where(function($query)use($userid){
	    		//where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
	    		$query->where('allow_look','')
	    		->whereOr('FIND_IN_SET("'.$userid.'",allow_look)');
	    	})
    		->where($where)->order('create_time desc')->select();
// 	    echo $news->getLastsql();dump($newsall);die;
//     		->paginate($pagesize)->toArray();
		if(empty($newsall)){
			$data ['code'] = 1001;
			$data ['msg'] = '请求成功';
			$data ['data'] = [];
			return json($data);
		}
		$newsall = collection($newsall)->toArray();
    	foreach ($newsall as $k=>$v){
    		$newsall[$k]['distance'] = round(getDistance($lat,$lng,$v['latitude'], $v['longitude']));
    	}
//     	dump($newsall);die;
    	$newsall = my_sort($newsall, 'distance', SORT_ASC,SORT_NUMERIC);
    	if (isset($this->post['page'])) {
    		$page = 30*($this->post['page'] -1);
    	}else{
    		$page = 0;
    	}
    	$result=array_slice($newsall,$page,30);
    	$data ['code'] = 1001;
    	$data ['msg'] = '请求成功';
    	$data ['data'] = $result;
    	return json($data);
    }
    //获取周边商城
	public function round($pagesize=30,$page=1){//return json(time());
		//经纬度
		$this->post = array_filter($this->post);
		$lng=$this->post['longitude'];
		$lat=$this->post['latitude'];
		$userid = $this->user_id;
		$where['user_id'] = $userid;
		$where['delete'] = 0;
		$friends=GroupFriendsModel::all($where);//dump($friends);die;
		$userids = array_column($friends, 'friends_user_id');
// 		$userids[] = $this->user_id;
		$tempArr = array_flip($userids);  
	    unset($tempArr[$this->user_id]);  
	    $userids = array_flip($tempArr);  //return json($userids);
		
		$news = new NewsModel();
		$rwhere['no_friends'] = 1;
		$rwhere['top'] = 0;
		$rwhere['state'] = 0;
		$rwhere['delete'] = 0;
		$newsall = $news->with('users')
		->field('news_id,user_id,place,year,month,day,is_lock,create_time,sub_question,sub_content,latitude,longitude,like_count,comment_count')
// 		->where(function($query)use($userids){
// 			//where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
// 			$query->where('user_id','exp',"find_in_set($userid,allow_look)");
// // 			->where('user_id','not in',$userids);
// 		})
		->where($rwhere)
		->where(function($query)use($userid,$userids){
			//where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
			$query->where("find_in_set($userid,allow_look)")
			->whereOr('user_id','not in',$userids);
		})
		->order('create_time desc')->select();//echo $news->getLastSql();die;
// 		return json($newsall);die;
		$total = $news->with('users')
		->field('news_id,user_id,place,year,month,day,is_lock,create_time,sub_question,sub_content,latitude,longitude,like_count,comment_count')
// 		->where(function($query)use($userids){
// 			//where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
// // 			$query//->where('allow_look','')
// // 			->where('user_id','not in',$userids);
// 			$query->where('user_id','exp',"find_in_set($userids,allow_look)");
// 		})
		->where($rwhere)
		->where(function($query)use($userid,$userids){
			//where('id','exp',' IN (1,3,8) ');"find_in_set($userid,allow_look)"
			$query->where("find_in_set($userid,allow_look)")
			->whereOr('user_id','not in',$userids);
		})->count();//echo $total;die;
		$topwhere['top'] = 1;
		$topwhere['delete'] = 0;
		$topwhere['no_friends'] = 1;
		$userone=$news::with('users')
		->field('news_id,user_id,place,year,month,day,is_lock,sub_question,sub_content,latitude,longitude,like_count,comment_count')
		->where($topwhere)->order('create_time desc')->find();//dump($userone);die;
		if(empty($newsall)){
			if ($userone) {
				$userone['distance'] = 0;
				$userone['sendtime'] = '';
				$data['code'] = 1001;
				$data['msg'] = '请求成功';
	    		$data['total'] = $total;
	    		$data['page'] = $page;
				$data['data'] = $userone;
				return json($data);
			}
			$data ['code'] = 1001;
			$data ['msg'] = '请求成功';
	    	$data ['total'] = 0;
	    	$data ['page'] = 1;
			$data ['data'] = [];
			return json($data);
		}
		$arr=array();
		$newsall = collection($newsall)->toArray();//return json($this->post);
		$today=strtotime(date('Y-m-d'));
		foreach ($newsall as $k=>$v){//return json($v);
			$v['distance'] = round(getDistance($lat,$lng,$v['latitude'], $v['longitude']));
			$intime=timediff(strtotime($v['create_time']),time());
			if (isset($this->post['time'])) {
				if ($intime['day'] < $this->post['time']) {
					if ($intime['day'] >= 3) {
						$v['sendtime'] ='7天内';
					}elseif($intime['day'] >= 1){
						$v['sendtime'] ='3天内';
					}elseif(strtotime($v['create_time']) < $today){//小任务显示时间为自然时间状态,昨天发的就显示昨天今天发的显示几个小时前
						// 					return json($intime);
						$v['sendtime'] ='昨天';
					}elseif($intime['hour'] > 1){
						$v['sendtime'] = $intime['hour'].'小时前';
					}elseif($intime['min'] >1){
						$v['sendtime'] = $intime['min'].'分钟前';
					}else{
						$v['sendtime'] = '1分钟内';
					}
					$arr[]=$v;
				}
			}else{
				if ($intime['day'] >= 7) {
					$v['sendtime'] ='7天前';
				}elseif ($intime['day'] >= 3) {
					$v['sendtime'] ='7天内';
				}elseif($intime['day'] >= 1){
					$v['sendtime'] ='3天内';
				}elseif(strtotime($v['create_time']) < $today){//小任务显示时间为自然时间状态,昨天发的就显示昨天今天发的显示几个小时前
					// 					return json($intime);
					$v['sendtime'] ='昨天';
				}elseif($intime['hour'] > 1){
					$v['sendtime'] = $intime['hour'].'小时前';
				}elseif($intime['min'] >1){
					$v['sendtime'] = $intime['min'].'分钟前';
				}else{
					$v['sendtime'] = '1分钟内';
				}
				$arr[]=$v;
			}
// 			$arr[]=$v;
		}//return json($arr);
// 		$newsall = my_sort($arr, 'distance', SORT_ASC,SORT_NUMERIC);
		$tmp = [];//return json($arr);
		foreach ($arr as $key => $val) {
			$tmp[$key] = $val['distance'];
		}
		array_multisort($tmp,SORT_ASC,$arr);//此处对数组进行降序排列；SORT_DESC按降序排列
// 		return json($arr);
		if (isset($this->post['page'])) {
			$start = 30*($page -1);
		}else{
			$start = 0;
		}
		$result=array_slice($arr,$start,30);
// 		return json($result);
		if ($userone && $page == 1) {
			$userone['distance'] = 0;
			$userone['sendtime'] = '';
			array_unshift($result,$userone);
		}
		$data ['code'] = 1001;
		$data ['msg'] = '请求成功';
	    $data ['total'] = $total;
	    $data ['page'] = $page;
		$data ['data'] = $result;
		return json($data);
	}
    //动态详情
    public function detail(){
    	$newsid =$this->post['news_id'];
    	$where['news_id'] = $newsid;
    	$where['state'] = 0;
    	$where['delete'] = 0;
    	$news = new NewsModel();
    	$newsall = $news->with('users,newsimgs')
    	->field('news_id,user_id,province,city,district,place,year,month,day,is_lock,question,content')
    	->where($where)->find();//->toArray();
    	if ($newsall['user_id'] == $this->user_id) {
    		$newsall['is_self'] = 1;
    	}else {
    		$newsall['is_self'] = 0;
    	}
    	$collect = new CollectModel();
    	$cwhere['delete'] = 0;
    	$cwhere['news_id'] = $newsid;
    	$cwhere['user_id'] = $this->user_id;
    	$iscol = $collect->where($cwhere)->find();
    	if ($iscol) {
    		$newsall['is_collect'] = 1;
    	}else{
    		$newsall['is_collect'] = 0;
    	}
    	if ($newsall) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['data'] = $newsall;
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '数据为空';
    	$data ['data'] = [];
    	return json($data);
    }
    
    //解锁
    public function Unlock(){
        $userid = $this->user_id;
    	$answer = $this->post['answer'];
    	$where['news_id'] = $this->post['news_id'];
    	$where['state'] = 0;
    	$where['delete'] = 0;
    	$news = new NewsModel();
    	$newsone = $news->where($where)->find();
    	if ($newsone['answer'] == $answer) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['data'] = [];
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '答案错误';
    	$data ['data'] = [];
    	return json($data);
    }
    public function Isallow(){
        $news = new NewsModel();
        $newswhere['news_id'] = $this->post['news_id'];
        $newswhere['delete'] = 0;
        $newsone = $news->where($newswhere)->find()->toArray();
        $allows = explode(',', $newsone['allow_look']);
        $userid = $this->user_id;
        if (in_array($userid ,$allows)) {
            $data ['code'] = 1001;
            $data ['msg'] = '允许';
            $data ['data'] = [];
            return json($data);
        }
        $data ['code'] = 1002;
        $data ['msg'] = '拒绝';
        $data ['data'] = [];
        return json($data);

    }
    public function test(){
        echo ROOT_PATH;die;
    }
    public function add(){
    	$userid = $this->user_id;
    	$this->post['user_id'] = $userid; // return json($this->post);
    	$this->post['sub_content'] = mb_substr($this->post['content'],0,70,'utf-8');
		if (isset($this->post['answer'])) {
			if ($this->post['answer']) {
				$this->post['is_lock']=1;
				$this->post['sub_question']=mb_substr($this->post['question'],0,70,'utf-8');
			}
		}
		if ($this->is_sys) {
			$this->post['top']=1;
		}
		
		//显示对某个分组好友展示，分隔
//         $this->post['group_id']=[17,30];
        // $this->post['group_id'] = ['001','002'];
		if (isset($this->post['group_id'])) {
			$rtrimstr = ltrim($this->post['group_id'], '[');
			$this->post['group_id'] = array_filter(explode(',',rtrim($rtrimstr, ']')));
			if (in_array('001', $this->post['group_id'])) {//推荐
				$this->post['is_recommend'] = 1;
                foreach ($this->post['group_id'] as $k=>$v){
                    if ($v === '001'){
                         array_splice($this->post['group_id'],$k,1);
                    }
                }
			}
			if (in_array('002', $this->post['group_id'])) {//
				$this->post['no_friends'] = 1;
				foreach ($this->post['group_id'] as $k=>$v){
                    if ($v === '002'){
                         array_splice($this->post['group_id'],$k,1);
                    }
                }
			}
            $groupids = $this->post['group_id'];
            if ($groupids) {
                $where['group_id']=['in', $groupids];//dump($where);die;
                $where['state'] = 0;
                $where['delete'] = 0;//dump($where);die;
                $groupfriendss = new GroupFriendsModel();
                $groupfriendarr = $groupfriendss->where($where)//->where($where)
                        ->select();//dump($groupfriendarr);die;
                $userids = array_column($groupfriendarr,"friends_user_id");//dump($userids);die;
                $userids[] = $this->user_id;
                $userids = array_flip(array_flip($userids));
                $useridstr = implode(",", $userids);
                $this->post['allow_look'] = $useridstr;//dump($this->post);die;
            } else{
                $this->post['allow_look'] = '';
            }
		}//return json($this->post);
		$news = new NewsModel($this->post);
    	$savenews = $news->allowField(true)->save();//添加
    	$saveid = $news->news_id;
    	$this->post['news_id'] = $news->news_id;
    	if (isset($this->post['news_img']) && !empty($this->post['news_img'])) {
    		$savePath=ROOT_PATH.'/public/uploads/user/'.$userid.'/news/'.$saveid;
    		if (!file_exists($savePath)||!is_dir($savePath)){
    			mkdir($savePath,0777,true);
    		}
    		$imgs=explode(',', $this->post['news_img']);//dump($imgs);die;
    		array_filter($imgs);
    		$mtnub=mt_rand(1000,9999);
    		foreach ($imgs  as $k=>$v){
    			$filename =date("ymdHis").$mtnub.$k;
    			$file=$savePath."/".$filename.".jpg";//图片存路径
    			$string =base64_decode($v);
    			file_put_contents($file,$string);//写入文件
    			$image =\think\Image::open($file);
    			$image->open($file);
    			$width = $image->width(); // 返回图片的宽度
    			$height = $image->height();// 按照原图的比例生成缩略图并保存为thumb.jpg
    			$image->thumb($width, $height)->save($savePath.'/thumb_'.$filename.'.jpg');
    			$suofile="$savePath/thumb_".$filename.".jpg";//图片入库路径
    			$endsuofile="/uploads/user/".$userid."/news/$saveid/thumb_".$filename.".jpg";//图片入库路径
    			$this->post['thumb_img']=$endsuofile;
    			$endfile="/uploads/user/".$userid."/news/$saveid/$filename.jpg";//图片入库路径
    			$this->post['news_img']=$endfile;
    			$bimg=new NewsImgModel($this->post);
    			$bimg->allowField(true)->save();
    		}
    	}
    	if (isset($this->post['lock_img']) && !empty($this->post['lock_img'])) {
    		$savePath=ROOT_PATH.'/public/uploads/user/'.$userid.'/lock_img/'.$saveid;
    		if (!file_exists($savePath)||!is_dir($savePath)){
    			mkdir($savePath,0777,true);
    		}
    		$imgs=explode(',', $this->post['lock_img']);//dump($imgs);die;
    		array_filter($imgs);
    		$mtnub=mt_rand(1000,9999);
    		foreach ($imgs  as $k=>$v){
    			$filename =date("ymdHis").$mtnub.$k;
    			$file=$savePath."/".$filename.".jpg";//图片存路径
    			$string =base64_decode($v);
    			file_put_contents($file,$string);//写入文件
    			$image =\think\Image::open($file);
    			$image->open($file);
    			$width = $image->width(); // 返回图片的宽度
    			$height = $image->height();// 按照原图的比例生成缩略图并保存为thumb.jpg
    			$image->thumb($width, $height)->save($savePath.'/thumb_'.$filename.'.jpg');
    			$suofile="$savePath/thumb_".$filename.".jpg";//图片入库路径
    			$endsuofile="/uploads/user/".$userid."/lock_img/$saveid/thumb_".$filename.".jpg";//图片入库路径
    			$this->post['thumb_img']=$endsuofile;
    			$endfile="/uploads/user/".$userid."/lock_img/$saveid/".$filename.".jpg";//图片入库路径
    			$this->post['lock_img']=$endfile;
    			$bimg=new LockImg($this->post);
    			$bimg->allowField(true)->save();
    		}
    	}
    	if ($savenews){
    		$data ['code'] = 1001;
    		$data ['msg'] = '添加成功 ';
    		$data ['data'] = [];
    		return json($data);
    	}else{
    		$data ['code'] = 1003;
    		$data ['msg'] = '添加失败！请您重新添加！ ';
    		$data ['data'] = [];
    		return json($data);
    	}
    }
    
    public function edit($news_id){
    
    	$m = Db::name('pxsk_news')->find($news_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($news_id){
    	$news=new NewsModel();
    	$deletenews=NewsModel::destroy(['news_id' => $this->post['news_id']]);
    	if($deletenews){
    		$newsimg=new NewsImgModel();
    		$imgurl=$newsimg->field('news_id,news_img,thumb_img')
    		->where("news_id=".$this->post['news_id'])->select();
    		$imgurl=collection($imgurl)->toArray();
    		if ($imgurl) {
    			foreach ($imgurl as $v){
    				$path=ROOT_PATH.'public'.$v['news_img'];
    				$pathtwo=ROOT_PATH.'public'.$v['thumb_img'];
    				if (file_exists($path)) {
    					@unlink($path);@unlink($pathtwo);
    				}
    				NewsImgModel::destroy(['news_id' => $this->post['news_id']]);
    			}
    		}
    		$data ['code'] = 1001;
    		$data ['msg'] = '删除成功';
    		$data ['data'] = [];
    		return json($data);
    	}else{
    		$data ['code'] = 1003;
    		$data ['msg'] = '删除失败';
    		$data ['data'] = [];
    		return json($data);
    	}
    	
    	
    	if(Db::name('pxsk_news')->delete($news_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['news_id']==''){
    		$result = $this->validate($data,'Pxsk_news.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_news')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_news.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_news')
			    	->where('news_id', $data['news_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}