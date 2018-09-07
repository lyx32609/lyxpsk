<?php 
namespace app\index\model;

use think\Model;

class Collect extends Model
{
	protected $table = 'pxsk_collect';
    protected $autoWriteTimestamp = true;
    //设置要隐藏的字段，
    //该设置只在toArray(),和subToArray()方法中起作用
    protected $hidden = ['create_time','update_time','state','delete'];
    public function news()
    {
    	return $this->hasOne('News','news_id','news_id')
    		->field('news_id,user_id,place,year,month,day,is_lock,question,content');
    }
    
    public function user()
    {
    	return $this->hasManyThrough('User','News');//,'news_id','user_id','collect_id'
    }
    
}