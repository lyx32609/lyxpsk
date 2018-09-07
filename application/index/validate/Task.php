<?php 
namespace app\index\validate;

use think\Validate;

class Task extends Validate
{
    protected $rule = [
            'msg'  =>  'require',
            'latitude'  =>  'require|float',
            'longitude'  =>  'require|float',
            'person_num'  =>  'require|number',
            'task_inte'  =>  'require|number',
            'read_count'  =>  'require|number',
            'like_count'  =>  'require|number',
            'hate_like'  =>  'require|number',
            'is_lock'  =>  'require|number',
            'update_time'  =>  'require|number',
            'create_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'msg.require'  =>  '小任务消息必填',
             'latitude.require'  =>  'latitude必填',
             'latitude.float'  =>  'latitude为小数',
             'longitude.require'  =>  'longitude必填',
             'longitude.float'  =>  'longitude为小数',
             'person_num.require'  =>  '可获取积分人数必填',
             'person_num.number'  =>  '可获取积分人数为数值',
             'task_inte.require'  =>  '小任务积分必填',
             'task_inte.number'  =>  '小任务积分为数值',
             'read_count.require'  =>  '阅读量必填',
             'read_count.number'  =>  '阅读量为数值',
             'like_count.require'  =>  '点赞量必填',
             'like_count.number'  =>  '点赞量为数值',
             'hate_like.require'  =>  '厌恶量必填',
             'hate_like.number'  =>  '厌恶量为数值',
             'is_lock.require'  =>  '是否上锁，加密必填',
             'is_lock.number'  =>  '是否上锁，加密为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'state.require'  =>  'state必填',
             'state.number'  =>  'state为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['business_id','user_id','msg','latitude','longitude','address','person_num','task_inte','read_count','like_count','hate_like','is_lock','update_time','create_time','state','delete'],
        'edit'  =>  ['business_id','user_id','msg','latitude','longitude','address','person_num','task_inte','read_count','like_count','hate_like','is_lock','update_time','create_time','state','delete'],
    ];
}