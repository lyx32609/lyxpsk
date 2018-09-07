<?php 
namespace app\index\validate;

use think\Validate;

class Version extends Validate
{
    protected $rule = [
            'version_name'  =>  'require',
            'version_num'  =>  'require',
            'state'  =>  'require|number',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require',
            'dalete'  =>  'require|number',
        ];

    protected $message = [
             'version_name.require'  =>  '版本必填',
             'version_num.require'  =>  'version_num必填',
             'state.require'  =>  '最新版本必填',
             'state.number'  =>  '最新版本为数值',
             'create_time.require'  =>  'create_time必填',
             'create_time.number'  =>  'create_time为数值',
             'update_time.require'  =>  'update_time必填',
             'dalete.require'  =>  'dalete必填',
             'dalete.number'  =>  'dalete为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['version_name','version_num','state','create_time','update_time','dalete'],
        'edit'  =>  ['version_name','version_num','state','create_time','update_time','dalete'],
    ];
}