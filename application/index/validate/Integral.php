<?php 
namespace app\index\validate;

use think\Validate;

class Integral extends Validate
{
    protected $rule = [
            'user_id'  =>  'require|number',
            'integral'  =>  'require|number',
            'type'  =>  'require|number',
            'task_id'  =>  'require|number',
            'success'  =>  'require|number',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'end_time'  =>  'require|number',
            'user_read'  =>  'require|number',
            'business_read'  =>  'require|number',
            'business_delete'  =>  'require|number',
        ];

    protected $message = [
             'user_id.require'  =>  '用户id必填',
             'user_id.number'  =>  '用户id为数值',
             'integral.require'  =>  '交易积分必填',
             'integral.number'  =>  '交易积分为数值',
             'type.require'  =>  '用户获取积分方式1浏览2收藏3到店4成交必填',
             'type.number'  =>  '用户获取积分方式1浏览2收藏3到店4成交为数值',
             'task_id.require'  =>  '小任务ID必填',
             'task_id.number'  =>  '小任务ID为数值',
             'success.require'  =>  '积分交易成功与否默认0未成功必填',
             'success.number'  =>  '积分交易成功与否默认0未成功为数值',
             'create_time.require'  =>  '创建时间必填',
             'create_time.number'  =>  '创建时间为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'end_time.require'  =>  '最终成交时间必填',
             'end_time.number'  =>  '最终成交时间为数值',
             'user_read.require'  =>  '用户是否看过默认为0没有必填',
             'user_read.number'  =>  '用户是否看过默认为0没有为数值',
             'business_read.require'  =>  '商家是否看过默认为0没有必填',
             'business_read.number'  =>  '商家是否看过默认为0没有为数值',
             'business_delete.require'  =>  '商家删除状态默认为0没有必填',
             'business_delete.number'  =>  '商家删除状态默认为0没有为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['user_id','business_id','integral','type','task_id','success','create_time','update_time','end_time','user_read','business_read','business_delete'],
        'edit'  =>  ['user_id','business_id','integral','type','task_id','success','create_time','update_time','end_time','user_read','business_read','business_delete'],
    ];
}