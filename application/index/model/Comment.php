<?php 
namespace app\index\model;

use think\Model;

class Comment extends Model
{
    protected $autoWriteTimestamp = true;
    //设置要隐藏的字段，
    //该设置只在toArray(),和subToArray()方法中起作用
    protected $hidden = ['update_time','state','delete'];
    public function users()
    {
    	//     	return $this->hasMany('User','user_id','user_id')->field('head_img,user_id,nick_name');
    	return $this->hasOne('User','user_id','user_id')->field('head_img,user_id,nick_name');
    	//     	return $this->hasOne('User','user_id','user_id')->bind('name');
    }
    
    public function userone()
    {
    	//     	return $this->hasMany('User','user_id','user_id')->field('head_img,user_id,nick_name');
    	return $this->hasOne('User','user_id','user_id')->field('head_img,user_id,nick_name');
    	//     	return $this->hasOne('User','user_id','user_id')->bind('name');
    }
    
    public function usertwo()
    {
    	//     	return $this->hasMany('User','user_id','user_id')->field('head_img,user_id,nick_name');
    	return $this->hasOne('User','user_id','friends_user_id')->field('head_img,user_id,nick_name');
    	//     	return $this->hasOne('User','user_id','user_id')->bind('name');
    }
    
    public function getroot($commentid)
    {
    	$comment=$this->find($commentid);//where()->
    	if ($comment['parent_id'] == 0) {
    		return $comment['comment_id'];
    	}else {
    		$this->getroot($commentid);
    	}
    }
    
    public function getCreateTimeAttr($value)
    {
    	return date('m.d H:i',$value);
    }
//     public function _getparentid($AuthRuleRes,$authRuleId,$clear=False){
//     	static $arr=array();
//     	if($clear){
//     		$arr=array();
//     	}
//     	foreach ($AuthRuleRes as $k => $v) {
//     		if($v['id'] == $authRuleId){
//     			$arr[]=$v['id'];
//     			$this->_getparentid($AuthRuleRes,$v['pid'],False);
//     		}
//     	}
//     	asort($arr);
//     	$arrStr=implode('-', $arr);
//     	return $arrStr;
//     }
}