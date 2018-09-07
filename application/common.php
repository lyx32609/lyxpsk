<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function timediff($begin_time,$end_time){
	if($begin_time<$end_time){
		$starttime=$begin_time;
		$endtime=$end_time;
	}else{
		$starttime=$end_time;
		$endtime=$begin_time;
	}
	$timediff=$endtime-$starttime;
	$days=intval($timediff/86400);
	$remain=$timediff;
	$hours=intval($remain/3600);
	$mins=intval($remain/60);
	$secs=$remain;
	$res=array("day"=>$days,"hour"=>$hours,"min"=>$mins,"sec"=>$secs);
	return$res;
}
function getDistance($lat1, $lng1, $lat2, $lng2){ //$lat1, $lng1, $lat2, $lng2
	$earthRadius = 6367000; //approximate radius of earth in meters
	$lat1 = ($lat1 * pi() ) / 180;
	$lng1 = ($lng1 * pi() ) / 180;
	$lat2 = ($lat2 * pi() ) / 180;
	$lng2 = ($lng2 * pi() ) / 180;
	$calcLongitude = $lng2 - $lng1;
	$calcLatitude = $lat2 - $lat1;
	$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
	$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	$calculatedDistance = $earthRadius * $stepTwo;
	return   round($calculatedDistance);
}
function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
{
	/* 根据一个字段进行排序，array_multisort() 可以用来一次对多个数组进行排序，或者根据某一维或多维对多维数组进行排序。
	 关联（string）键名保持不变，但数字键名会被重新索引。
	排序顺序标志：
	SORT_ASC - 按照上升顺序排序
	SORT_DESC - 按照下降顺序排序
	排序类型标志：
	SORT_REGULAR - 将项目按照通常方法比较
	SORT_NUMERIC - 将项目按照数值比较
	SORT_STRING - 将项目按照字符串比较 */
	if (is_array($arrays)) {
		foreach ($arrays as $array) {
			if (is_array($array)) {
				$key_arrays[] = $array[$sort_key];
			} else {
				return false;
			}
		}
	} else {
		return false;
	}
	array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
	return $arrays;
}
function ismobile() {
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
		return true;

	//此条摘自TPM智能切换模板引擎，适合TPM开发
	if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
		return true;
	//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ($_SERVER['HTTP_VIA']))
		//找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
	//判断手机发送的客户端标志,兼容性有待提高
	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array(
				'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
		);
		//从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
	//协议法，因为有可能不准确，放到最后判断
	if (isset ($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}
function postRequest( $api, array $params = array(), $timeout = 30 ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $api );
	// 以返回的形式接收信息
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	// 设置为POST方式
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ));
	// 不验证https证书
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
	'Accept: application/json',
	) );
	// 发送数据
	$response = curl_exec( $ch );
	// 不要忘记释放资源
	curl_close( $ch );
	return $response;
}
function file_get_contents_post($url, $post){
	//$query = http_build_query($post);
	$options['http'] = array(
			'timeout'=>60,
			'method' => 'POST',
			'header' => 'Content-type:application/x-www-form-urlencoded',
			'content' => $post
	);
	$result = file_get_contents($url,false, stream_context_create($options));
	return $result;
}
function arrayTostring($e){
	if( is_array($e) && count($e)>0 ){
		foreach($e as $k=>$v){
			if( is_array($v)){
				$e[$k] = arrayTostring($v);
			}else{
				$e[$k] = "$v";
			}
		}
	}
	return $e;
}