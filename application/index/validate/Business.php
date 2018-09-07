<?php 
namespace app\index\validate;

use think\Validate;

class Business extends Validate
{
    protected $rule = [
            'business_name'  =>  'require',
            'address'  =>  'require',
            'district'  =>  'require',
            'manage'  =>  'require',
            'longitude'  =>  'require|float',
            'latitude'  =>  'float',
            'collect'  =>  'require',
            'collect_inte'  =>  'require',
            'reach'  =>  'require',
            'reach_inte'  =>  'require',
            'clinch'  =>  'require',
            'clinch_inte'  =>  'require',
            'create_time'  =>  'require|number',
            'update_time'  =>  'require|number',
            'state'  =>  'require|number',
        ];

    protected $message = [
             'business_name.require'  =>  'business_name必填',
             'address.require'  =>  '商家具体位置前台百度定位获取必填',
             'district.require'  =>  '属于区域 县区级别必填',
             'manage.require'  =>  '县区再低一级别 到标志建筑物必填',
             'longitude.require'  =>  '经度必填',
             'longitude.float'  =>  '经度为小数',
             'latitude.float'  =>  '纬度为小数',
             'collect.require'  =>  '是否开启收藏有礼必填',
             'collect_inte.require'  =>  '用户收藏可以得到多少积分必填',
             'reach.require'  =>  'dao到店必填',
             'reach_inte.require'  =>  '用户到店可以得到多少积分必填',
             'clinch.require'  =>  '成交得到的积分必填',
             'clinch_inte.require'  =>  '成交获得的积分必填',
             'create_time.require'  =>  '创建时间必填',
             'create_time.number'  =>  '创建时间为数值',
             'update_time.require'  =>  'update_time必填',
             'update_time.number'  =>  'update_time为数值',
             'state.require'  =>  '1浏览有礼2收藏有礼3到店4成交必填',
             'state.number'  =>  '1浏览有礼2收藏有礼3到店4成交为数值',
         ];

	
    protected $scene = [
        'add'   =>  ['business_name','address','integral','district','manage','place','longitude','latitude','collect','collect_inte','reach','reach_inte','clinch','clinch_inte','head_img','manage_id','info','create_time','update_time','state'],
        'edit'  =>  ['business_name','address','integral','district','manage','place','longitude','latitude','collect','collect_inte','reach','reach_inte','clinch','clinch_inte','head_img','manage_id','info','create_time','update_time','state'],
    ];
}

