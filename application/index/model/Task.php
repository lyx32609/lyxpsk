<?php 
namespace app\index\model;

use think\Model;

class Task extends Model
{
	protected $table = 'pxsk_task';
    protected $autoWriteTimestamp = true;
    //设置要隐藏的字段，
    //该设置只在toArray(),和subToArray()方法中起作用
    protected $hidden = ['create_time','update_time','state','delete'];
    public function taskimgs()
    {
    	return $this->hasMany('TaskImg','task_id','timg_id');
    }

}