<?php
//判断接口工作模式 (Added in July 15, 2017|Powered By DingStudio Copyright 2017 All Rights Reserved)
if (@$_POST['cors_domain'] != '') {
	header('Access-Control-Allow-Origin: '.$_POST['cors_domain']);//允许跨域请求
	header('Access-Control-Allow-Credentials: true');//允许跨域数据传送
}
else {
	header('Access-Control-Allow-Origin: *');//允许跨域请求
}

/**
* 引入自定义数据处理类以减少本文件体积，详细说明请转到该文件源码查看其注释。
*/
require_once(dirname(__FILE__)."/function/api_class.php");//引入API数据类
require_once(dirname(__FILE__)."/function/core.php");//引入API数据类

//启动安全检测//
require_once(dirname(__FILE__)."/function/security.class.php");//引入安全防护模块
$secapp = new Security();
$secapp->checkDomain(@parse_url($_POST['cors_domain'])[host]);
$secapp->create_CSRFToken();
//安全检测结束//

/**
* Ajax异步登录-数据拉取
* 本接口支持数据以json和xml形式输出
* 调用方法：api.php?format=xml ->使用XML输出 api.php?format=json -> 使用JSON输出
* Powered By DingStudio.Club(Tech) Copyright 2017 All Right Reserved
* 
*/

api_myinit();//执行API数据输出

function api_myinit() {//API数据输出模块
	$type = ToolServlet::GetQueryString('format');
	if (!$type) {//未传递参数时的过程
		Response::errorHandler();
	}
	else if ($type == 'xml') {//XML数据输出
		Response::xmlEncode(200,'success',checkLogin());
	}
	else if ($type == 'json') {//JSON数据输出
		Response::jsonEncode(200,'success',checkLogin());
	}
	else if ($type == 'ajaxlogin') {//Ajax登录
		if (isset($_POST['username']) && isset($_POST['userpwd'])) {//执行用户SSO登陆过程
			$username = $_POST['username'];//传入账号POST值到变量
			$password = md5($_POST['userpwd']);//传入密码POST值到变量
			if (CoreServlet::SSOCheck($username, $password)=='authed') {//SSO开始登录认证
				$arr = array(
					'authcode' => 1
				);
				Response::jsonEncode(200,'Single sign-on authority service report the account login successfully...',$arr);
				exit(0);
			}
			else {//SSO认证失败的ajax返回
				$arr = array(
					'authcode' => 0
				);
				Response::jsonEncode(403,'Single sign-on authority service report the account information you provide is wrong.',$arr);
				exit(0);
			}
		}
		$arr = array(
			'authcode' => 0
		);
		Response::jsonEncode(403,'Access denied...',$arr);
		exit(0);
	}
	else {//非法参数错误处理
		Response::errorHandler();
	}
}

function checkLogin() {
	if(CoreServlet::SSOCheckExist("0")=="authed") {
		$arr = array(
			'authcode' => 1,
			'username' => $_COOKIE['dingstudio_sso']
		);
	}
	else {
		$arr = array(
			'authcode' => 0
		);
	}
	return $arr;
}
