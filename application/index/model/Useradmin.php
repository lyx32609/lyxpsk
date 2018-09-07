<?php 
namespace app\index\model;

use think\Model;

class Useradmin extends Model
{
	protected $table = 'pxsk_user';
	protected $pk = 'user_id';
    protected $autoWriteTimestamp = true;
    // 关闭自动写入create_time字段
    protected $createTime = false;
    protected $dateFormat = 'Y/m/d H:i';
    protected $hidden = ['create_time','update_time','password','state','delete'];
    protected $readonly = ['create_time'];
    public function getSexAttr($value)
    {
    	$status = [0=>'未知',1=>'男',2=>'女'];
    	return $status[$value];
    }

    public function maillists()
    {
    	return $this->hasMany('MailList','news_id','nimg_id');
    }




}