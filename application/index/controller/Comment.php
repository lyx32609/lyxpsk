<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Comment as CommentModel;
use app\index\model\News;
class Comment extends Userbase
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and message like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_comment')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
		$this->assign('page', $page);
    	return view();
    }
    
    public function add(){
    	// $this->post['comment_id'] = $this->comment_id;
        $this->post['user_id'] = $this->user_id;
    	$comment = new CommentModel($this->post);
    	// 过滤post数组中的非数据表字段数据
    	$res = $comment->allowField(true)->save();
    	if ($res) {
    		$newswhere['news_id'] = $this->post['news_id'];
    		$news = new News();
    		$news->where($newswhere)->setInc('comment_count');
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
    
    public function commentadd(){//对留言进行留言
    	// $this->post['comment_id'] = $this->comment_id;
    	$this->post['user_id'] = $this->user_id;
    	$where['comment_id'] = $this->post['parent_id'];
    	$where['delete'] = 0;
    	$comment = new CommentModel();
    	$iscomment = $comment->where($where)->find();//dump($iscomment);die;
    	if ($iscomment) {
    		$iscomment = $iscomment->toArray();//return json($iscomment);
//     		$this->post['root_comment_id'] = $comment->getroot($iscomment['comment_id']);
    		$this->post['news_id'] = $iscomment['news_id'];
    		$this->post['friends_user_id'] = $iscomment['user_id'];
    		$comment->data($this->post);
    		$res = $comment->allowField(true)->save();
    		if ($res) {
//     			$newswhere['news_id'] = $this->post['news_id'];
//     			$news = new News();
//     			$news->where($newswhere)->setInc('comment_count');
    			$data['code'] = 1001;
    			$data['data'] = [];
    			$data['msg'] = '添加成功';
    			return json($data);
    		}
    	}
    	// 过滤post数组中的非数据表字段数据
    	
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
    	return view('edit');
    }
    
    public function isself(){//对留言进行留言
    	// $this->post['comment_id'] = $this->comment_id;
//    	$this->post['user_id'] =
    	$where['comment_id'] = $this->post['comment_id'];
        $where['user_id'] = $this->user_id;
        $where['delete'] = 0;
    	$comment = new CommentModel();
    	$iscomment = $comment->where($where)->find();//dump($iscomment);die;
    	if ($iscomment) {
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '添加成功';
    		return json($data);
    	}
    	// 过滤post数组中的非数据表字段数据
    	 
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '参数错误';
    	return json($data);
    }
    
    public function del(){
    	// $this->post['comment_id'] = $this->comment_id;
    	// $where['user_id'] = $this->user_id;
    	$where['comment_id'] = $this->post['comment_id'];
    	$comment = new CommentModel();
        $newsarr = $comment->where($where)->find();
        $where['news_id'] = $newsarr['news_id'];
        $where['user_id'] = $this->user_id;
    	$param['delete'] = 1;
    	// 过滤post数组中的非数据表字段数据
    	$res = $comment->allowField(['delete'])->save($param,$where);
//     	$res = $comment->allowField(true)->save();
    	if ($res) {
    		$newswhere['news_id'] = $newsarr['news_id'];
    		$news = new News();
    		$news->where($newswhere)->setDec('comment_count');
    		$data['code'] = 1001;
    		$data['data'] = [];
    		$data['msg'] = '请求成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '不能删除';
    	return json($data);
    }
    //获取某个动态所有评论
    public function newslist($pagesize=30){
    	$where['news_id'] = $this->post['news_id'];
        $where['delete'] = 0;
    	$comment = new CommentModel();
    	$list = $comment->with('userone,usertwo')->where($where)->order('comment_id desc')
    		->paginate($pagesize)->toArray();
    	if ($list) {
    		$data['code'] = 1001;
    		$data['total'] = $list['total'];
    		$data['page'] = $list['current_page'];
    		$data['data'] = $list['data'];
    		$data['msg'] = '请求成功';
    		return json($data);
    	}
    	$data['code'] = 1002;
    	$data['data'] = [];
    	$data['msg'] = '请求失败';
    	return json($data);
    	return view('edit');
    }
    
    public function edit(){
    	$commentid=$this->comment_id;
    	$savePath=ROOT_PATH.'public/uploads/comment/'.$commentid.'/head';
    	if (!file_exists($savePath)||!is_dir($savePath)){
    		mkdir($savePath,0777,true);
    	}
    	if (isset($this->post['img'])) {//头像保存更新
    		$filename =date("ymdHis");
    		$file=$savePath."/".$filename.".png";
    		$myfile='/uploads/comment'."/".$commentid.'/head/'.$filename.".png";
    		$string =base64_decode($this->post['img']);
    		file_put_contents($file,$string);
    		$image = \think\Image::open($file);
    		$width = $image->width(); // 返回图片的宽度
    		$height = $image->height();// 按照原图的比例生成缩略图并保存为thumb.jpg
    		$image->thumb($width, $height)->save($savePath.'/thumb_'.$filename.'.jpg');
    		$endfile="/uploads/comment/".$commentid."/head/thumb_".$filename.".jpg";//图片入库路径
    		$this->post['head_img']=$myfile;
    		$this->post['thumb_img']=$endfile;
    	}
    	$date = $this->post;//dump($token);die;
    	$comment=new commentModel();
    	$issave=$comment->allowField(true)->save($date,['comment_id' => $commentid]);
    	$commentone =$comment->get(['comment_id' => $commentid]);
    	if($issave!==false){
    		$end['code'] = 1001;
    		$end['msg'] = '更新成功！ ';
    		$end['data'] = $commentone;
    		return json($end);
    	}else{
    		//$this->error("注册失败",U("Home/comment/register"),3);
    		$end['code'] = 1003;
    		$end['msg'] = '更新失败！';
    		$end['data'] = $commentone;
    		return json($end);
    	}
    	
    	
    	$m = Db::name('pxsk_comment')->find($comment_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($comment_id){
    	
    	if(Db::name('pxsk_comment')->delete($comment_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['comment_id']==''){
    		$result = $this->validate($data,'Pxsk_comment.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_comment')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_comment.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_comment')
			    	->where('comment_id', $data['comment_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}