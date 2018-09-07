<?php 
namespace app\index\validate;

use think\Validate;

class Integral_news extends Validate
{
    protected $rule = [
            'business_id'  =>  'require|number',
            'user_id'  =>  'require|number',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'business_id.require'  =>  '商家id必填',
             'business_id.number'  =>  '商家id为数值',
             'user_id.require'  =>  '商家id必填',
             'user_id.number'  =>  '商家id为数值',
             'create_time.require'  =>  '创建时间必填',
             'create_time.number'  =>  '创建时间为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'state.require'  =>  '0没看1看过必填',
             'state.number'  =>  '0没看1看过为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['business_id','user_id','msg','newsurl','create_time','update_time','state','delete'],
        'edit'  =>  ['business_id','user_id','msg','newsurl','create_time','update_time','state','delete'],
    ];
}