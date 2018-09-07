<?php 
namespace app\index\validate;

use think\Validate;

class Friends extends Validate
{
    protected $rule = [
            'business_id'  =>  'number',
            'title'  =>  'require',
            'url'  =>  'url',
            'state'  =>  'require|number',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'business_id.number'  =>  'business_id为数值',
             'title.require'  =>  'title必填',
             'url.url'  =>  'url为URL',
             'state.require'  =>  'state必填',
             'state.number'  =>  'state为数值',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['business_id','title','head_img','pics_img','url','state','create_time','update_time','delete'],
        'edit'  =>  ['business_id','title','head_img','pics_img','url','state','create_time','update_time','delete'],
    ];
}
