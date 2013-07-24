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

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){  
    if(function_exists("mb_substr")){  
        if($suffix)  
             return mb_substr($str, $start, $length, $charset)."...";  
        else 
             return mb_substr($str, $start, $length, $charset);  
    }  
    elseif(function_exists('iconv_substr')) {  
        if($suffix)  
             return iconv_substr($str,$start,$length,$charset)."...";  
        else 
             return iconv_substr($str,$start,$length,$charset);  
    }  
    $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";  
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";  
    $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";  
    $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";  
    preg_match_all($re[$charset], $str, $match);  
    $slice = join("",array_slice($match[0], $start, $length));  
    if($suffix) return $slice."…";  
    return $slice;
}

function highlight($str,$key_arr){
    $param_temp = array();
    preg_match_all('/'.join('|',$key_arr).'/i',$str,$matches);
    foreach($matches[0] as $value){
        $param_temp[$value] = "<font style='color:#fff;background:#FF9632'>".$value ."</font>";
    }
    $str2 = strtr($str,$param_temp);
    return $str2;
}

function write_log($action){
	$ary=array();
	$loginuser = session("loginuser");
	$ary["operator"] = $loginuser["userId"];
	$ary["ip"] = get_client_ip();
	$ary["operateContent"] = $action;
	$ary["operateDate"] = date("Y-m-d H:i:s");
	M("OperateLog")->add($ary);
}
?>