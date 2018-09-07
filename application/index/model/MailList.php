<?php 
namespace app\index\model;

use think\Model;

class MailList extends Model
{
	protected $table = 'pxsk_mail_list';
    protected $autoWriteTimestamp = true;
    //设置要隐藏的字段，
    //该设置只在toArray(),和subToArray()方法中起作用
    protected $hidden = ['create_time','update_time','state','delete'];

}