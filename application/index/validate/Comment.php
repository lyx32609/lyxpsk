<?php 
namespace app\index\validate;

use think\Validate;

class Comment extends Validate
{
    protected $rule = [
            'business_id'  =>  'number',
            'news_id'  =>  'number',
            'user_id'  =>  'require|number',
            'message'  =>  'require',
            'create_time'  =>  'require|number',
            'state'  =>  'require|number',
            'update_time'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'business_id.number'  =>  '商家id为数值',
             'news_id.number'  =>  '商家发布的第几条动态id为数值',
             'user_id.require'  =>  '用户id必填',
             'user_id.number'  =>  '用户id为数值',
             'message.require'  =>  '消息留言必填',
             'create_time.require'  =>  '创建时间必填',
             'create_time.number'  =>  '创建时间为数值',
             'state.require'  =>  '0没看1看过必填',
             'state.number'  =>  '0没看1看过为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['business_id','news_id','user_id','message','parent_id','create_time','state','update_time','delete'],
        'edit'  =>  ['business_id','news_id','user_id','message','parent_id','create_time','state','update_time','delete'],
    ];
}
