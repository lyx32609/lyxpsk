<?php 
namespace app\index\model;

use think\Model;

class Chat extends Model
{
	protected $table = 'pxsk_chat';
	//设置要隐藏的字段，
	//该设置只在toArray(),和subToArray()方法中起作用
	protected $hidden = ['create_time','update_time','state','delete'];
    protected $autoWriteTimestamp = true;


}