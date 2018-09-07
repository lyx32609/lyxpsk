<?php 
namespace app\index\controller;
use think\Db;
use app\index\model\NewsLike as NewsLikeModel;
use app\index\model\News as NewsModel;
class Newslike extends Userbase
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and cate_name like binary '%$keyword%'  and url like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_cate')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function add(){
    	$this->post['user_id'] = $this->user_id;//return json($this->post);
    	$newslike = new NewsLikeModel($this->post);
    	$news = new NewsModel();
    	if (isset($this->post['delete'])) {
    		unset($this->post['delete']);
    	}
    	$newslikewhere['user_id'] = $this->post['user_id'];
    	$newslikewhere['news_id'] = $this->post['news_id'];
    	$islike = $newslike->where($newslikewhere)->find();//return json($islike);
    	$newswhere['news_id'] = $this->post['news_id'];
    	if ($islike) {
//     		$islike = collection($islike)->toArray();return json($islike);
    		$iswhere['like_id'] = $islike['like_id'];
    		if ($islike['delete'] ==0) {//取消点赞
    			$this->post['delete'] = 1;
    			//$news->where($newswhere)->->where('id', 1)->setDec('score');
    			$qu = $newslike->allowField(['delete'])->save($this->post,$iswhere);
    			if ($qu) {
    				$news->where($newswhere)->setDec('like_count');
    				$count = $news->where($newswhere)->find();
    				$end['code'] = 1001;
    				$end['msg'] = '请求成功';
    				$end['data'] = $count['like_count'];
    			}
//     			$user->allowField(true)->save($_POST,['id' => 1]);
    		}else{//添加点赞
    			$this->post['delete'] = 0;
    			$qu = $newslike->allowField(['delete'])->save($this->post,$iswhere);
    			if ($qu) {
    				$news->where($newswhere)->setInc('like_count');
    				$count = $news->where($newswhere)->find();
    				$end['code'] = 1001;
    				$end['msg'] = '请求成功';
    				$end['data'] = $count['like_count'];
    			}
    		}
    		return json($end);
    	}
    	$savenum = $newslike->allowField(true)->save();
    	$news->where($newswhere)->setInc('like_count');
    	$count = $news->where($newswhere)->find();
    	if ($savenum){
    		$end ['code'] = 1001;
    		$end ['msg'] = '请求成功 ';
    		$end ['data'] = $count['like_count'];
    		return json($end);
    	}
    	$end ['code'] = 1003;
    	$end ['msg'] = '添加失败！请您重新添加！ ';
    	$end ['data'] = [];
    	return json($end);
    }
    
    public function edit($cate_id){
    
    	$m = Db::name('pxsk_cate')->find($cate_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($cate_id){
    
    	if(Db::name('pxsk_cate')->delete($cate_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['cate_id']==''){
    		$result = $this->validate($data,'Pxsk_cate.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_cate')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_cate.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_cate')
			    	->where('cate_id', $data['cate_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}
	