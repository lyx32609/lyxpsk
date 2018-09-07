<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
    	return view();
    	$useragent  = strtolower($_SERVER["HTTP_USER_AGENT"]);
    	// pc电脑
    	$is_pc = strripos($useragent,'windows nt');
    	if($is_pc){
    		return view();
    	}else{
    		$this->redirect('/index.php/index/index/mobile');
    	}
    	
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function mobile()
    {
    	return view();
    }
    function upload(){//下载安卓iOS客户端
    	// $file_name="cookie.jpg";
    	$file_name="pxsk.apk"; //文件名称
    	//用以解决中文不能显示出来的问题
    	$file_name=iconv("utf-8","gb2312",$file_name); //文件路径
    	$file_sub_path= $_SERVER['DOCUMENT_ROOT']."/";
    	$file_path=$file_sub_path.$file_name;
    	//首先要判断给定的文件存在与否
    	if(!file_exists($file_path)){
    		echo "没有该文件";
    		return ;
    	}
    	$fp=fopen($file_path,"r");
    	$file_size=filesize($file_path);
    	//下载文件需要用到的头
    	Header("Content-type: application/octet-stream");
    	Header("Accept-Ranges: bytes");
    	Header("Accept-Length:".$file_size);
    	Header("Content-Disposition: attachment; filename=".$file_name);
    	$buffer=1024;
    	$file_count=0;
    	//向浏览器返回数据
    	while(!feof($fp) && $file_count<$file_size){
    		$file_con=fread($fp,$buffer);
    		$file_count+=$buffer;
    		echo $file_con;
    	}
    	fclose($fp);
    }
}
