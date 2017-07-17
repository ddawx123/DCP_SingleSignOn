<?php 
/**
 * 使用Get的方式返回：challenge和capthca_id 此方式以实现前后端完全分离的开发模式 专门实现failback
 * @author Tanxu
 */
//error_reporting(0);
require_once dirname(__FILE__) . '/class.geetestlib.php';
require_once dirname(__FILE__) . '/config.php';
$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
session_start();

$user_ip = getClientIP(); //获取客户端IP地址

$data = array(
	"user_id" => "test", # 网站用户id
	"client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
	"ip_address" => $user_ip # 请在此处传输用户请求验证时所携带的IP
);

$status = $GtSdk->pre_process($data, 1);
$_SESSION['gtserver'] = $status;
$_SESSION['user_id'] = $data['user_id'];
echo $GtSdk->get_response_str();

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
?>