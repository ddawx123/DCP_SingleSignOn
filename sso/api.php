<?php
/*
//判断接口工作模式 (Added in July 15, 2017|Powered By DingStudio Copyright 2017 All Right Reserved)
if (@$_POST['cors_domain'] != '') {
	header('Access-Control-Allow-Origin: '.$_POST['cors_domain']);//允许跨域请求
	header('Access-Control-Allow-Credentials: true');//允许跨域数据传送
}
else {
	header('Access-Control-Allow-Origin: *');//允许跨域请求
}
*/

/**
* 引入自定义数据处理类以减少本文件体积，详细说明请转到该文件源码查看其注释。
*/
require_once(dirname(__FILE__)."/function/core.php");//引入API数据类

/*
//启动安全检测//
require_once(dirname(__FILE__)."/function/security.class.php");//引入安全防护模块
$secapp = new Security();
$secapp->checkDomain(@parse_url($_REQUEST['cors_domain'])[host]);
$secapp->create_CSRFToken();
//安全检测结束//
*/

/**
* Ajax异步登录-数据拉取
* 本接口支持数据以json和xml形式输出
* 调用方法：api.php?format=xml ->使用XML输出 api.php?format=json -> 使用JSON输出
* Powered By DingStudio.Club(Tech) Copyright 2017 All Right Reserved
* 
*/

switch (ToolServlet::GetQueryString('format')) {
    case 'json':
    $response = Response::getInstance('json');
    break;
    case 'xml':
    $response = Response::getInstance('xml');
    break;
    case 'other':
    $response = Response::getInstance('null');
    break;
    default:
    Response::getInstance('null')->errorHandler(405, '抱歉，请指定一个有效且受系统支持的数据输出格式。');
    break;
}
api_myinit();//执行API数据输出

function api_myinit() {//API数据输出模块
	global $response;
	$type = ToolServlet::GetQueryString('action');
	switch($type) {
		case "login":
		ajaxLogin();
		break;
		case "status":
		$response->make(200,'success',checkLogin());
		break;
		case "verify":
		verifyToken();
		break;
		case "logout":
		setcookie("dingstudio_sso", "", time()-3600, "/", constant('cross_domain_config'));
		setcookie("dingstudio_ssotoken", "", time()-3600, "/", constant('cross_domain_config'));
		setcookie("dingstudio_ssopasswd", "", time()-3600, "/", constant('cross_domain_config'));
		$response->make(200, 'Logout successfully.');
		break;
		default:
		$response->make(500, 'Could not open this module.');
		break;
	}
}

function ajaxLogin() {
	global $response;
	if (isset($_POST['username']) && isset($_POST['userpwd'])) {//执行用户SSO登陆过程
		$username = htmlspecialchars($_POST['username']);//传入账号POST值到变量
		$password = md5(htmlspecialchars($_POST['userpwd']));//传入密码POST值到变量
		if (CoreServlet::SSOCheck($username, $password)=='authed') {//SSO开始登录认证
			$arr = array(
				'authcode' => 1,
				'usertoken'	=>	sha1($_POST['username'].$_POST['userpwd'].date('YmdH',time()))
			);
			$response->make(200,'Single sign-on authority service report the account login successfully...',$arr);
			exit(0);
		}
		else {//SSO认证失败的ajax返回
			$arr = array(
				'authcode' => 0
			);
			$response->make(403,'Single sign-on authority service report the account information you provide is wrong.',$arr);
			exit(0);
		}
	}
	$arr = array(
		'authcode' => 0
	);
	$response->make(403,'Access denied...',$arr);
	exit(0);
}

function checkLogin() {
	if(CoreServlet::SSOCheckExist("0")=="authed") {
		$sqlconn = MySQLInstance::getInstance()->connect();//通过单例方式建立MySQL数据库连接
		if ($sqlconn->connect_error) {
			$uid = null;
			$username = null;
		}
		else {
			$username = $_COOKIE['dingstudio_sso'];
			$sqlcode = "select * from users where (username='{$username}')";//查询数据库检测账户密码是否匹配
			$result = $sqlconn->query($sqlcode);//执行上述SQL语句
			$result = $result->fetch_array();//提取数据
			$uid = $result['id'];
			$token = $result['usertoken'];
		}
		$arr = array(
			'authcode' => 1,
			'uid'	=>	$uid,
			'token' => $token
		);
	}
	else {
		$arr = array(
			'authcode' => 0
		);
	}
	return $arr;
}

function verifyToken() {
	global $response;
	if (ToolServlet::GetQueryString('token') == '' || ToolServlet::GetQueryString('reqtime') == '') {
		$response->make(405,'Blank token or signature key, please try again later.');
	}
	$request_token = ToolServlet::GetQueryString('token');
	$sqlconn = MySQLInstance::getInstance()->connect();//通过单例方式建立MySQL数据库连接
	if ($sqlconn->connect_error) {
		$response->make(500,'Could not connect mysql server, please try again later or contact your system administrator.');
	}
	$sqlcode = "select * from users where (usertoken='{$request_token}')";//使用token查询数据库搜索用户信息
	$result = $sqlconn->query($sqlcode);//执行上述SQL语句
	$result = $result->fetch_array();
	if ($result) {
		$uname = $result['username'];
		$newtoken = sha1(date('YmdHis',time()));
		$userdata = array(
			'username'	=>	$result['username'],
			'usermail'	=>	$result['email'],
			'newtoken'	=>	$newtoken
		);
		$sqlcode = "update users set usertoken='{$newtoken}' where (usertoken='{$request_token}')";//刷新token
		$result = $sqlconn->query($sqlcode);//执行上述SQL语句
		MySQLInstance::getInstance()->disconnect();
		$response->make(200, 'Well, your token was vaild.', $userdata);
	}
	else {
		$response->make(403,'Invaild token, please try again later.');
	}
}
