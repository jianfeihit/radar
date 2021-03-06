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

// 获取经纬度
function getPostion($ip){
	$req = "http://api.map.baidu.com/location/ip?ak=F454f8a5efe5e577997931cc01de3974&coor=bd09ll&ip=".$ip;
	$rsp = file_get_contents($req);
	return json_decode($rsp,true);
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
		$param_temp[$value] = "<font color='#ff0000'>".$value ."</font>";
	}
	$str2 = strtr($str,$param_temp);
	return $str2;
}

function highlightWord($str,$word){
	$param_temp = array();
	preg_match_all('/'.join('|',$key_arr).'/i',$str,$matches);
	$param_temp[$word] = "<font color='#ff0000'>".$word ."</font>";
	$str2 = strtr($str,$param_temp);
	return $str2;
}

function heightlighttext($key, $orignkey, $color = "red") {
	$tmp = explode ( "|", $key );
	for($i = 0; $i < count ( $tmp ); $i ++) {
		$str_rep = "<span style='color:#F00'>" . $tmp [$i] . "</span>";
		$orignkey = preg_replace ( "/($tmp[$i])/i", "<span style='color:#F00'>\\1</span>", $orignkey );
	}
	return $orignkey;
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
function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null){
	$config = C('THINK_EMAIL');
	vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
	$mail             = new PHPMailer(); //PHPMailer对象
	$mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
	$mail->IsSMTP();  // 设定使用SMTP服务
	$mail->SMTPDebug  = 0;// 关闭SMTP调试功能
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;// 启用 SMTP 验证功能
	//$mail->SMTPSecure = 'ssl';// 使用安全协议
	$mail->Host       = $config['SMTP_HOST'];  // SMTP 服务器
	$mail->Port       = $config['SMTP_PORT'];  // SMTP服务器的端口号
	$mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名
	$mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码
	$mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
	$replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
	$replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
	$mail->AddReplyTo($replyEmail, $replyName);
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	$mail->AddAddress($to, $name);
	if(is_array($attachment)){ // 添加附件
		foreach ($attachment as $file){
			is_file($file) && $mail->AddAttachment($file);
		}
	}
	return $mail->Send() ? true : $mail->ErrorInfo;
}

/**
 * 下载文件
 * @param string $file
 *               被下载文件的路径
 * @param string $name
 *               用户看到的文件名
 */
 function download($file,$name=''){
    $fileName = $name ? $name : pathinfo($file,PATHINFO_FILENAME);
    $filePath = realpath($file);
    
    $fp = fopen($filePath,'rb');
    
    if(!$filePath || !$fp){
        header('HTTP/1.1 404 Not Found');
        echo "Error: 404 Not Found.(server file path error)<!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding -->";
        exit;
    }
    
    $fileName = $fileName .'.'. pathinfo($filePath,PATHINFO_EXTENSION);
    $encoded_filename = urlencode($fileName);
    $encoded_filename = str_replace("+", "%20", $encoded_filename);
    
    header('HTTP/1.1 200 OK');
    header( "Pragma: public" );
    header( "Expires: 0" );
    header("Content-type: application/octet-stream");
    header("Content-Length: ".filesize($filePath));
    header("Accept-Ranges: bytes");
    header("Accept-Length: ".filesize($filePath));
    
    $ua = $_SERVER["HTTP_USER_AGENT"];
    if (preg_match("/MSIE/", $ua)) {
        header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
    } else if (preg_match("/Firefox/", $ua)) {
        header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
    } else {
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
    }
    
    // ob_end_clean(); <--有些情况可能需要调用此函数
    // 输出文件内容
    fpassthru($fp);
    exit;
 }

?>