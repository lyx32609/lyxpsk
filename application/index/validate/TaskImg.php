<?php 
namespace app\index\validate;

use think\Validate;

class Task_img extends Validate
{
    protected $rule = [
            'task_id'  =>  'require|number',
            'task_img'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'task_id.require'  =>  '哪条动态必填',
             'task_id.number'  =>  '哪条动态为数值',
             'task_img.require'  =>  '链接图片必填',
             'create_time.require'  =>  '添加时间必填',
             'create_time.number'  =>  '添加时间为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['business_id','task_id','thumb_img','task_img','create_time','update_time','delete'],
        'edit'  =>  ['business_id','task_id','thumb_img','task_img','create_time','update_time','delete'],
    ];
}
