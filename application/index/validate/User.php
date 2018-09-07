<?php 
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
            'password'  =>  'require',
            'tel'  =>  'require',
            'integral'  =>  'require|number',
            'sex'  =>  'require|number',
            'email'  =>  'email',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'password.require'  =>  '密码必填',
             'tel.require'  =>  '手机号必填',
             'integral.require'  =>  '总积分必填',
             'integral.number'  =>  '总积分为数值',
             'sex.require'  =>  '1男0女必填',
             'sex.number'  =>  '1男0女为数值',
             'email.email'  =>  '电子邮件为EMAIL',
             'create_time.require'  =>  '用户创建时间必填',
             'create_time.number'  =>  '用户创建时间为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'state.require'  =>  'state必填',
             'state.number'  =>  'state为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['user_name','password','tel'],
        'edit'  =>  ['user_name','password','integral','head_img','sex','age','name','email','signature','create_time','update_time','state','delete'],
    ];
}