<?php 
namespace app\index\validate;

use think\Validate;

class News extends Validate
{
    protected $rule = [
            'msg'  =>  'require',
            'longitude'  =>  'require|float',
            'latitude'  =>  'require|float',
            'update_time'  =>  'require|number',
            'create_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'msg.require'  =>  '发表的内容必填',
             'longitude.require'  =>  '经度必填',
             'longitude.float'  =>  '经度为小数',
             'latitude.require'  =>  'latitude必填',
             'latitude.float'  =>  'latitude为小数',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'state.require'  =>  'state必填',
             'state.number'  =>  'state为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
       // 'add'   =>  ['business_id','user_id','msg','longitude','latitude','address','top','question','answer','read_count','like_count','hate_count','is_lock','update_time','create_time','state','delete'],
        //'edit'  =>  ['business_id','user_id','msg','longitude','latitude','address','top','question','answer','read_count','like_count','hate_count','is_lock','update_time','create_time','state','delete'],
    ];
}