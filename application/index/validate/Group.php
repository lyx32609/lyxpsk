<?php 
namespace app\index\validate;

use think\Validate;

class Group extends Validate
{
    protected $rule = [
            'user_id'  =>  'require|number',
            '	
group_name'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'user_id.require'  =>  '用户id必填',
             'user_id.number'  =>  '用户id为数值',
             '	
group_name.require'  =>  '分类必填',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'state.require'  =>  'state必填',
             'state.number'  =>  'state为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        //'add'   =>  ['user_id','group_name','parent_id','create_time','update_time','state','delete'],
        'edit'  =>  ['group_name']
        ];
}
