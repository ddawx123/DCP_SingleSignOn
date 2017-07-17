<?php
/**
* SSO应用核心库文件，在这里包含了SSO的一些实现方法类+函数过程。
* @package DingStudio_SSO_CoreServlet
* @subpackage SSO/Core 核心类库
* @copyright 2016-2017 DingStudio All Right Reserved
*/

require_once(dirname(__FILE__)."/config.php");//引入应用配置文件
require_once(dirname(__FILE__)."/api_class.php");//引入API数据输出类

/**
* 核心认证类
* Copyright 2017 DingStudio All Right Reserved
*/
class CoreServlet {
	
	/**
	* 用途：原有SSO会话检查
	* @param string $special 特殊运行模式（这是个预留变量）
	* return string
	*/
	public static function SSOCheckExist($special = '0') {
		$nowtime = date('Ymdhis',time());//获取即时服务器时间
		if(isset($_COOKIE['dingstudio_sso']) && isset($_COOKIE['dingstudio_ssotoken']) && $nowtime - $_COOKIE['dingstudio_ssotoken'] <= 3600) {//检测SSO状态，如是否存在可信授权信息以及会话超时情况
			return "authed";//存在合法会话且处于时效期限内，返回自动登陆
		}
		else {
			return "noauth";//不存在合法会话或有效密钥超时，返回请求登陆
		}
	}
	
	/**
	* 用途：SSO登陆认证
	* @param string $username 用户账号
	* @param string $password 用户密码
	* return string
	*/
	public static function SSOCheck($username, $password) {
		if(isset($username) and isset($password)) {
			$sqlconn = MySQLInstance::getInstance()->connect();//通过单例方式建立MySQL数据库连接
			if ($sqlconn->connect_error) {
				return "error-654";
			}
			else {
				$sqlcode = "select * from users where (username='{$username}') and (password='{$password}')";//查询数据库检测账户密码是否匹配
				$result = $sqlconn->query($sqlcode);//执行上述SQL语句
				if ($result->num_rows > 0) {//登陆成功后
					//$dtoken = md5(uniqid());
					$dtoken = date('Ymdhis',time());//产生SSO令牌码（使用时间）
					$client_ipaddr = ToolServlet::GetClientIpAddress();
					setcookie("dingstudio_sso", $username, time() + 3600,  "/", constant('cross_domain_config'));
					setcookie("dingstudio_ssotoken", $dtoken, time() + 3600,  "/", constant('cross_domain_config'));
					setcookie("dingstudio_ssopasswd", $password, time() + 3600, "/", constant('cross_domain_config'));
					$sqlcode = "update users set usertoken='{$dtoken}' where username='{$username}'";//更新SSO令牌码到数据库
					$result = $sqlconn->query($sqlcode);//执行上述SQL语句
					$sqlcode = "select lastIP from users where (username='{$username}') and (password='{$password}')";//查询已有IP记录
					$result = $sqlconn->query($sqlcode);//执行上述SQL语句
					$result = $result->fetch_array();//提取数据
					if (!$result or $result['lastIP'] == null) {
						$sqlcode = "update users set lastIP='{$client_ipaddr}' where username='{$username}'";//更新用户客户端IP到数据库以便备案
						$result = $sqlconn->query($sqlcode);//执行上述SQL语句
					}
					else {
						$client_ipaddr = $client_ipaddr . ',' .  $result['lastIP'];//拼接登录IP历史记录
						$sqlcode = "update users set lastIP='{$client_ipaddr}' where username='{$username}'";//更新用户客户端IP到数据库以便备案
						$result = $sqlconn->query($sqlcode);//执行上述SQL语句
					}
					$sqlcode = "select lastOPTime from users where (username='{$username}') and (password='{$password}')";//查询已有用户历史操作时间
					$result = $sqlconn->query($sqlcode);//执行上述SQL语句
					$result = $result->fetch_array();//提取数据
					if (!$result or $result['lastOPTime'] == null) {
						$current_timestamp = date('Y/m/d h:i:s',time());
						$sqlcode = "update users set lastOPTime='{$current_timestamp}' where username='{$username}'";//更新用户操作时间到数据库以便备案
						$result = $sqlconn->query($sqlcode);//执行上述SQL语句
					}
					else {
						$current_timestamp = date('Y/m/d h:i:s',time()).','.$result['lastOPTime'];
						$sqlcode = "update users set lastOPTime='{$current_timestamp}' where username='{$username}'";//更新用户操作时间到数据库以便备案
						$result = $sqlconn->query($sqlcode);//执行上述SQL语句
					}
					return "authed";
				}
				else {
					return "noauth";
				}
				MySQLInstance::getInstance()->disconnect();//关闭数据库连接
			}
		}
	}
	
	/**
	* 用途：SSO用户注销
	* @param string $callbackUrl 登出后的回调地址
	* return string
	*/
	public static function SSOLogout($callbackUrl) {
		if(!isset($callbackUrl)) {//判断是否存在回调地址
			die('Illegal operation');
		}
		$username = $_COOKIE['dingstudio_sso'];
		setcookie("dingstudio_sso", "", time()-3600, "/", constant('cross_domain_config'));
		setcookie("dingstudio_ssotoken", "", time()-3600, "/", constant('cross_domain_config'));
		setcookie("dingstudio_ssopasswd", "", time()-3600, "/", constant('cross_domain_config'));
		$sqlconn = MySQLInstance::getInstance()->connect();//通过单例方式建立MySQL数据库连接
		if($sqlconn->connect_error) {
			return "error-654";
		}
		else {
			$sqlcode = "update users set usertoken='' where username='{$username}'";//清空SSO令牌码
			$result = $sqlconn->query($sqlcode);//执行上述SQL语句
		}
		header('Location: '.$callbackUrl);
		exit(0);
	}
}

/**
* MySQL数据库实例化类
* 如何调用？在其他程序中通过require_once包含本类库，然后使用：
* $sqlconn = MySQLInstance::getInstance()->connect();
* $sqlconn 为连接句柄变量，可以自行设置。数据库连接信息配置文件位于 config.php 中！
* Copyright 2017 DingStudio All Right Reserved
*/
class MySQLInstance {
	static private $_instance = null;
	static private $_connectSource;
	
	private function __construct() {//防止在外部实例化该类
		//TODO
	}

	static public function getInstance() {
		if(!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function connect() {//建立数据库连接
		if(!self::$_connectSource) {
			self::$_connectSource = mysqli_connect(constant('mysql_server_address'), constant('mysql_server_username'), constant('mysql_server_password'));

			if(!self::$_connectSource) {
				throw new Exception('mysql connect error ' . mysqli_error());
				//die('MySQL Connect Error' . mysql_error());
			}

			mysqli_select_db(self::$_connectSource,constant('mysql_server_dbname'));
			mysqli_query(self::$_connectSource,"set names UTF8");
		}
		return self::$_connectSource;
	}
	
	public function disconnect() {//数据库连接释放模块
		if(!self::$_connectSource) {
			exit(0);
		}
		mysqli_close(self::$_connectSource);//释放数据库连接
	}
}

/**
* 辅助工具类
* Copyright 2017 DingStudio All Right Reserved
*/
class ToolServlet {
	
	/**
	* 用途：检测浏览器URL参数携带情况
	* @param string $gstr 要检测的参数字段名
	* return string
	*/
	public static function GetQueryString($gstr) {
		$val = !empty($_GET[$gstr]) ? $_GET[$gstr] : null;
		return $val;
	}

	public static function GetClientIpAddress() {
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
			$ip = getenv("HTTP_CLIENT_IP"); 
		}  
     	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}
    	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
			$ip = getenv("REMOTE_ADDR");
		}
        else if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		else {
			$ip = "unknown";
		}
		return ($ip); 
	}
}