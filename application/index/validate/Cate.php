<?php 
namespace app\index\validate;

use think\Validate;

class Cate extends Validate
{
    protected $rule = [
            'cate_name'  =>  'require',
            'url'  =>  'require',
            'parent_id'  =>  'require',
            'state'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'cate_name.require'  =>  '分类必填',
             'url.require'  =>  '链接地址必填',
             'parent_id.require'  =>  '父id必填',
             'state.require'  =>  'state必填',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'delete.require'  =>  'delete必填',
             'delete.number'  =>  'delete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['cate_name','url','parent_id','state','create_time','update_time','delete'],
        'edit'  =>  ['cate_name','url','parent_id','state','create_time','update_time','delete'],
    ];
}