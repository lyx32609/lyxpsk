<?php 
namespace app\index\validate;

use think\Validate;

class Trail extends Validate
{
    protected $rule = [
            'user_id'  =>  'number',
            'business_id'  =>  'number',
            'longitude'  =>  'require|float',
            'latitude'  =>  'require|float',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'user_id.number'  =>  '用户id为数值',
             'business_id.number'  =>  '商家id为数值',
             'longitude.require'  =>  '经度必填',
             'longitude.float'  =>  '经度为小数',
             'latitude.require'  =>  '纬度必填',
             'latitude.float'  =>  '纬度为小数',
             'create_time.require'  =>  '创建时间必填',
             'create_time.number'  =>  '创建时间为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'state.require'  =>  'state必填',
             'state.number'  =>  'state为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['user_id','business_id','longitude','latitude','address','create_time','update_time','state','delete'],
        'edit'  =>  ['user_id','business_id','longitude','latitude','address','create_time','update_time','state','delete'],
    ];
}