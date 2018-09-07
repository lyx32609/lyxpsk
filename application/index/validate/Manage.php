<?php 
namespace app\index\validate;

use think\Validate;

class Manage extends Validate
{
    protected $rule = [
            'latitude'  =>  'require|float',
            'longitude'  =>  'require|float',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'deleted'  =>  'require|number',
        ];

    protected $message = [
             'latitude.require'  =>  'latitude必填',
             'latitude.float'  =>  'latitude为小数',
             'longitude.require'  =>  'longitude必填',
             'longitude.float'  =>  'longitude为小数',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'deleted.require'  =>  'deleted必填',
             'deleted.number'  =>  'deleted为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['parent_id','latitude','longitude','create_time','update_time','deleted'],
        'edit'  =>  ['parent_id','latitude','longitude','create_time','update_time','deleted'],
    ];
}
