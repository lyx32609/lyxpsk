<?php 
namespace app\index\validate;

use think\Validate;

class News_img extends Validate
{
    protected $rule = [
            'news_id'  =>  'require|number',
            'news_img'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'news_id.require'  =>  '哪条动态必填',
             'news_id.number'  =>  '哪条动态为数值',
             'news_img.require'  =>  '链接图片必填',
             'create_time.require'  =>  '添加时间必填',
             'create_time.number'  =>  '添加时间为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'state.require'  =>  'state必填',
             'state.number'  =>  'state为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['business_id','news_id','thumb_img','news_img','create_time','update_time','state','delete'],
        'edit'  =>  ['business_id','news_id','thumb_img','news_img','create_time','update_time','state','delete'],
    ];
}