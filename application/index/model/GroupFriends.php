<?php 
namespace app\index\model;

use think\Model;

class GroupFriends extends Model
{
	protected $table = 'pxsk_group_friends';
    protected $autoWriteTimestamp = true;
    //设置要隐藏的字段，
    //该设置只在toArray(),和subToArray()方法中起作用
    protected $hidden = ['create_time','update_time','state','delete'];
    public function groups()
    {
    	return $this->hasOne('Group','group_id','group_id')
    	->where('delete=0 and state=0');
    		//->field('group_id,nickname');
    }
    
    public function users()//friends_user_id
    {
    	return $this->hasOne('User','user_id','friends_user_id')
    	->field('user_id,nick_name,head_img');
    }
    
    public function chats()//friends_user_id
    {
    	return $this->hasMany('Chat','send_user_id','friends_user_id')
    	->where('delete=0 and state=0')->field('send_user_id,msg')
    	->order('id desc')->limit(1);
    }




}