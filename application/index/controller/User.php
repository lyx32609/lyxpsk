<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Session;
use app\index\model\User as UserModel;
use app\index\model\News as NewsModel;

class User extends Userbase//Controller//
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and user_name like binary '%$keyword%'  and password like binary '%$keyword%'  and img like binary '%$keyword%'  and tel like binary '%$keyword%'  and name like binary '%$keyword%'  and email like binary '%$keyword%'  and signature like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_user')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    function Auto(){
    	$token=$this->post['token'];
    	$user_id=$this->user_id;
    	$session= new Session();
    	$sessionone = $session->where(array('token'=>$token))->find();//查session表是否存在
    	$time=time()+2592000;//return json($sessionone);
    	//base64_encode(hash_hmac('sha1',$urlEncodeStr,$appkey,true))
    	$update['expires']=$time;//shuaxin刷新一个月不登录就过期2592000
    	$update['token']= base64_encode($sessionone['session_tel'].'user'.$time);
    	$result=$session->allowField(true)
    		->save($update,['session_id' => $sessionone['session_id']]);//更新session表信息
    	$user= new UserModel;
    	$userone=$user->where(array('user_id'=>$sessionone['user_id'],'tel'=>$sessionone['session_tel']))->find();
    	if ($result) {
    		$data['code'] = 1001;
    		$data['data'] = $userone;
    		$data['token'] = $update['token'];
    		$data['msg'] = '自动登录成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['msg'] = '自动登录失败';
    	$data['data'] = [];
    	$data['token'] = [];
    	return json($data);
    
    }
    
    public function edit(){
    	$userid=$this->user_id;//$token=43;//echo ROOT_PATH;die;
    	$user=new UserModel();
//     	$userone = $user->where($haswhere)->find();
        $userdetail = $user->get(['user_id' => $userid]);
        if (isset($this->post['nick_name'])) {
        	$haswhere['nick_name'] = $this->post['nick_name'];
        	$haswhere['user_id'] = ['neq',$userid];
        	$userdhas = $user->where($haswhere)->find();
        	if ($userdhas) {
        		$end['code'] = 1003;
	        	$end['msg'] = '该昵称已被占用';
	        	$end['data'] = $userone;
	        	return json($end);
        	}
        }
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
    	$issave=$user->allowField(true)->save($date,['user_id' => $userid]);
    	$userone =$user->get(['user_id' => $userid]);
    	if($issave!==false){
    		$end['code'] = 1001;
            $end['token'] = $date['token'];
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
    
    
    public  function detail(){
    	if (isset($this->post['user_id'])) {
    		$user=new UserModel();
    		$where['user_id'] = $this->post['user_id'];
    		$userone = $user->where($where)
    		->field('head_img,nick_name,age,sex,place,signature')->find();
    		if ($userone) {
    			$data ['code'] = 1001;
    			$data ['msg'] = '请求成功';
    			$data ['data'] = $userone;
    			return json($data);
    		}
    		$data ['code'] = 1002;
    		$data ['msg'] = '请求失败';
    		$data ['data'] = [];
    		return json($data);
    	}
    }
    function logout(){//退出
    	//$session=M('session');
    	$where['user_id'] = $this->user_id;
    	$session=new Session();
    	$res = $session->destroy($where);
    	if ($res) {
    		$data ['code'] = 1001;
    		$data ['token'] = '';
    		// $data ['user'] = [];
    		$data ['msg'] = '退出成功';
    		return json($data);
    	}else{
    		$user = new UserModel();
    		$userone = $user->get(['user_id' => $this->user_id]);
    		$data ['code'] = 1003;
    		$data ['token'] = $this->token;
    		// $data ['user'] = $userone;
    		$data ['msg'] = '退出失败';
    		return json($data);
    	}
    }
    
    public function Newslst(){//获取用户所有动态
    	$news = new NewsModel();//$user = User::get(['name' => 'thinkphp']);
    	$userlst=$news::with('newsimgs,users')->get(['user_id' => $this->user_id]);
    	if ($userlst) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功';
    		$data ['data'] = $userlst;
    		return json($data);
    	}
    	$data ['code'] = 1003;
    	$data ['msg'] = '数据为空';
    	$data ['data'] = [];
    	return json($data);
    }
    public function delete($tel){
    
    	if(Db::name('pxsk_user')->delete($tel)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    //根据手机号查询用户添加好友在usersys中查询
    public function findone(){
    	$user = new UserModel();
    	$where['tel'] = $this->post['tel'];
    	$userone = $user->where($where)->find();//->field('')
    	if ($userone) {
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