<?php 
namespace app\index\model;

use think\Model;

class Group extends Model
{
	protected $table = 'pxsk_group';
    protected $autoWriteTimestamp = true;
    //设置要隐藏的字段，
    //该设置只在toArray(),和subToArray()方法中起作用
    protected $hidden = ['create_time','update_time','state','delete'];
    public function groupfriends()
    {
    	return $this->hasMany('Groupfriends','art_id');
    }
}