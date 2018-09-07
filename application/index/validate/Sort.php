<?php 
namespace app\index\validate;

use think\Validate;

class Sort extends Validate
{
    protected $rule = [
            'sort_name'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'sort_name.require'  =>  '分类必填',
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
        'add'   =>  ['sort_name','parent_id','create_time','update_time','state','delete'],
        'edit'  =>  ['sort_name','parent_id','create_time','update_time','state','delete'],
    ];
}
