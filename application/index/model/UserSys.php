<?php 
namespace app\index\model;

use think\Model;

class UserSys extends Model
{
	protected $table = 'pxsk_user_sys';
	protected $pk = 'user_sys_id';
    protected $autoWriteTimestamp = true;
    // 关闭自动写入create_time字段
    protected $createTime = false;
    protected $dateFormat = 'Y/m/d H:i';
    protected $hidden = ['create_time','update_time','password','state','delete'];
    protected $readonly = ['create_time'];
    
    public function users()
    {
    	return $this->hasOne('user','user_id','friends_user_id')
    		->field('user_id,head_img,nick_name,signature,sex');
    }
    
    public function imgs()
    {
    	return $this->hasOne('user','user_id','friends_user_id')
    	->bind('head_img,nick_name');
    }



}