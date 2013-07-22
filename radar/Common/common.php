<?php
// 获取host
function getHost($url){
	$arr_url = parse_url($url);
	return $arr_url['host'];
}

function getMainHost($url){
    $vhost = getHost($url);
    return substr($vhost, strpos($vhost,".")+1);
}

// 获取IP,输入必须是host
function getIP($url){
	return gethostbyname($url);
}

// 获取网络运营商
function getISPAndCity($ip){
	$req = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=".$ip;
    $rsp = file_get_contents($req);
    return json_decode($rsp);
}

// 字符串判断是否开头函数
function startWith($str, $needle) {
    return strpos($str, $needle) === 0;
}

?>