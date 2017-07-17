<?php
/**
 * 返回二次验证结果
 */
header("Content-Type: application/json; charset=UTF-8");
//error_reporting(0);
require_once dirname(__FILE__) . '/class.geetestlib.php';
require_once dirname(__FILE__) . '/config.php';
session_start();

$user_ip = getClientIP(); //获取客户端IP地址

$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);

$data = array(
    "user_id" => $_SESSION['user_id'], # 网站用户id
    "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
    "ip_address" => $user_ip # 请在此处传输用户请求验证时所携带的IP
);


if ($_SESSION['gtserver'] == 1) {   //服务器正常
	if ($_SERVER['REQUEST_METHOD']!="POST") {
		header("Content-Type: text/plain; charset=UTF-8");
		die('405 Method Not Allowed');
	}
    $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
    if ($result) {
        echo '{"status":"success"}';
	}
	else{
        echo '{"status":"fail"}';
    }
}
else{  //服务器宕机,走failback模式
    if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
        echo '{"status":"success"}';
    }
	else{
        echo '{"status":"fail"}';
    }
}

/**
 * 客户端IP地址获取方法
 * @return string $ip 用户IP地址
 */
function getClientIP() {
	global $ip;
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else $ip = "127.0.0.1";
	return $ip;
}

/**
 * 服务端HTTP_POST请求方法
 * @param string $url post目标地址
 * @param mixed $data post数据
 * @param string $optional_headers 可选HTTP头
 * @return mixed 接口返回数据
 */
function do_post_request($url, $data, $optional_headers = null) {
    $params = array('http' => array(
        'method' => 'POST',
        'content' => $data
    ));
    if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
    }
    $response = @stream_get_contents($fp);
    if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
    }
    return $response;
}
?>
