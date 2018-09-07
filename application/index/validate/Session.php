<?php 
namespace app\index\validate;

use think\Validate;

class Session extends Validate
{
    protected $rule = [
            'user_id'  =>  'require|number',
            'session_tel'  =>  'require',
            'expires'  =>  'require',
            'token'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'user_id.require'  =>  '用户ID必填',
             'user_id.number'  =>  '用户ID为数值',
             'session_tel.require'  =>  '手机号必填',
             'expires.require'  =>  '到期时间必填',
             'token.require'  =>  '客户端token必填',
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
        'add'   =>  ['user_id','business_id','session_tel','expires','token','create_time','update_time','state','delete'],
        'edit'  =>  ['user_id','business_id','session_tel','expires','token','create_time','update_time','state','delete'],
    ];
}