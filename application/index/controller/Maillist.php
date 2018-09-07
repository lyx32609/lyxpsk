<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\MailList as MailListModel;
use app\index\model\User as UserModel;

class Maillist extends Userbase//Controller
{
    public function manager($keyword='')
    {
    	$wheresql = '1=1';
    	if($keyword!='')
    		$wheresql .= " and mail_list_name like binary '%$keyword%' ";
    		
    	$list = Db::name('pxsk_mail_list')->where($wheresql)->paginate(25);
    	$page = $list->render();
    	
    	$this->assign('list', $list);
	$this->assign('page', $page);

    	return view();
    }
    
    public function userlist($pagesize=30){
    	$user = new UserModel();
    	$maillist = $user::with('newsimgs')->where(['user_id' => $this->user_id])->paginate($pagesize);
    	if($maillist){
    		$data ['code'] = 1001;
    		$data ['msg'] = '请求成功 ';
    		$data ['data'] = $maillist;
    		return json($data);
    	}else{
    		$data ['code'] = 1003;
    		$data ['msg'] = '数据为空！ ';
    		$data ['data'] = [];
    		return json($data);
    	}
    	return view('edit');
    }
    
    public function add(){
    	$maillist = new MailListModel($this->post);
    	$savemail = $maillist->allowField(true)->save();//添加
    	if($savemail){
    		$data ['code'] = 1001;
    		$data ['msg'] = '添加成功 ';
    		$data ['data'] = [];
    		return json($data);
    	}else{
    		$data ['code'] = 1003;
    		$data ['msg'] = '添加失败！请您重新添加！ ';
    		return json($data);
    	}
    	return view('edit');
    }
    
    public function edit($mail_list_id){
    	$maillist=new MailListModel();
    	$issave=$maillist->allowField(true)
    		->save($this->post,['mail_list_id' => $this->post['mail_list_id']]);
    	if ($issave) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '修改成功 ';
    		$data ['data'] = [];
    		return json($data);
    	}else{
    		$data ['code'] = 1003;
    		$data ['msg'] = '修改失败';
    		$data ['data'] = [];
    		return json($data);
    	}
    	$m = Db::name('pxsk_mail_list')->find($mail_list_id);
    	$this->assign('m', $m);
    	return view();
    }
    
    public function delete($mail_list_id){
    	$maillist=new MailListModel();
    	$del['delete']=1;
    	$issave=$maillist->allowField(true)
    		->save($del,['mail_list_id' => $this->post['mail_list_id']]);
    	if ($issave) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '删除成功 ';
    		$data ['data'] = [];
    		return json($data);
    	}else{
    		$data ['code'] = 1003;
    		$data ['msg'] = '删除失败';
    		$data ['data'] = [];
    		return json($data);
    	}
    	
    	
    	$del=MailListModel::destroy($this->post['mail_list_id']);
    	if ($del) {
    		$data ['code'] = 1001;
    		$data ['msg'] = '删除成功 ';
    		$data ['data'] = [];
    		return json($data);
    	}else{
    		$data ['code'] = 1003;
    		$data ['msg'] = '删除失败';
    		$data ['data'] = [];
    		return json($data);
    	}
    	if(Db::name('pxsk_mail_list')->delete($mail_list_id)){
    		$this->success('删除成功！','manager');
    	}else{
    		$this->error('删除失败！');
    	}
    }
    
    public function save(){
    	
    	$data=input('post.');
    	if($data['mail_list_id']==''){
    		$result = $this->validate($data,'Pxsk_mail_list.add');
			if(true !== $result){
			    $this->error($result);
			}else{
				Db::name('pxsk_mail_list')->insert($data);
				$this->success('添加成功！','manager');
			}
    	}else{
    		$result = $this->validate($data,'Pxsk_mail_list.edit');
			if(true !== $result){
			    $this->error($result);
			}else{
    			Db::name('pxsk_mail_list')
			    	->where('mail_list_id', $data['mail_list_id'])
			    	->update($data);
    			$this->success('更新成功！','manager');
    		}
    	
    	}
    }
    
}