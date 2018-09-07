<?php 
namespace app\index\model;

use think\Model;

class News extends Model
{
	protected $table = 'pxsk_news';
    protected $autoWriteTimestamp = true;
    //设置自动完成的字段，支持键值对数组和索引数组
    //新增和更新时都会使用
    //如：['name'=>'zhangsan','sex'=>'男']
    // ['name','sex']
    protected $auto = [];
    //新增 自动完成列表
    //只在新增数据的时候使用
    protected $insert = [];
    //更新 自动完成列表
    //只在更新数据的时候使用
    protected $update = [];
    //用来标记当前操作被修改的字段
    //如 ['name','sex']
    protected $change = [];
    //设置要隐藏的字段，
    //该设置只在toArray(),和subToArray()方法中起作用
    protected $hidden = ['update_time','state','delete'];//
    //依赖方法,model类会自动调用解析auto数组
    //我们只需配置auto数组即可
    protected function autoCompleteData($auto = []){}
    
    public function newsimgs()
    {
    	 return $this->hasMany('NewsImg','news_id','news_id')->field('news_id,thumb_img,news_img');
    }
    
    public function lockimgs()
    {
    	return $this->hasMany('LockImg','news_id','news_id')->field('news_id,thumb_img,lock_img');
    }
    
    public function users()
    {
//     	return $this->hasMany('User','user_id','user_id')->field('head_img,user_id,nick_name');
    	return $this->hasOne('User','user_id','user_id')->field('head_img,user_id,nick_name');
//     	return $this->hasOne('User','user_id','user_id')->bind('name');
    }


}