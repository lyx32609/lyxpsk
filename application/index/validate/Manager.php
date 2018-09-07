<?php 
namespace app\index\validate;

use think\Validate;

class Manager extends Validate
{
    protected $rule = [
            'manager_name'  =>  'require',
            'district_id'  =>  'require|number',
            'manage_id'  =>  'require|number',
            'number'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
            'delete'  =>  'require|number',
        ];

    protected $message = [
             'manager_name.require'  =>  '管理员姓名必填',
             'district_id.require'  =>  'qu区域ID必填',
             'district_id.number'  =>  'qu区域ID为数值',
             'manage_id.require'  =>  'zui最小区域ID必填',
             'manage_id.number'  =>  'zui最小区域ID为数值',
             'number.require'  =>  '代号编号ID必填',
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
        'add'   =>  ['head_img','manager_name','district_id','manage_id','number','create_time','update_time','state','delete'],
        'edit'  =>  ['head_img','manager_name','district_id','manage_id','number','create_time','update_time','state','delete'],
    ];
}
