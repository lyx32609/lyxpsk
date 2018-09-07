<?php 
namespace app\index\validate;

use think\Validate;

class Inte_type extends Validate
{
    protected $rule = [
            'typename'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'typename.require'  =>  '获取积分类型必填',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'state.require'  =>  'state必填',
             'state.number'  =>  'state为数值',
             'delete.require'  =>  '删除状态必填',
             'delete.number'  =>  '删除状态为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['typename','create_time','update_time','state','delete'],
        'edit'  =>  ['typename','create_time','update_time','state','delete'],
    ];
}