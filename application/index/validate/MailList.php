<?php 
namespace app\index\validate;

use think\Validate;

class Mail_list extends Validate
{
    protected $rule = [
            'mail_list_name'  =>  'require',
            'user_id'  =>  'require|number',
            'state'  =>  'require|number',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'dalete'  =>  'require|number',
        ];

    protected $message = [
             'mail_list_name.require'  =>  '版本必填',
             'user_id.require'  =>  '用户ID必填',
             'user_id.number'  =>  '用户ID为数值',
             'state.require'  =>  '最新版本必填',
             'state.number'  =>  '最新版本为数值',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'dalete.require'  =>  'dalete必填',
             'dalete.number'  =>  'dalete为数值',
         ];

	
    protected $scene = [
        //'add'   =>  ['mail_list_name','user_id','business_id','sort','state','create_time','update_time','dalete'],
       // 'edit'  =>  ['mail_list_name','user_id','business_id','sort','state','create_time','update_time','dalete'],
    ];
}