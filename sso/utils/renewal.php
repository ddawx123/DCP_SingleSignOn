<?php
/**
* SSO会话续期模块，默认SSO会话有效期为1小时。
* 通过用户在1小时内访问业务应用，业务应用后端后向本文件发起请求实现会话自动续期。
* 如果用户在1小时内没有访问任何与本SSO统一身份认证系统相关联的业务应用，会话将会在认证起始时间的1小时后失效。
* 失效后用户访问各个接入本SSO统一身份认证系统的网站需要重新走SSO统一登录认证流程。。。
* 业务应用续期调用方法（如通过file_get_contents调用获得返回值）：renewal.php?session=delay
* 必须传入session参数值为delay，且必须在会话尚未过期的时段方可调用，其他未授权对象的调用会被全部重定向到登录页面。
* Copyright 2017 DingStudio All Rights Reserved
*/

header("Content-Type: text/html; charset=UTF-8");
if (!file_exists(dirname(__FILE__)."/../function/config.php") && !file_exists(dirname(__FILE__)."/function/core.php")) {
	die("抱歉，我们没有找到某些系统文件。请检查系统文件完整性！");
}
require_once(dirname(__FILE__)."/../function/core.php");
if(CoreServlet::SSOCheckExist("0")=="authed" && ToolServlet::GetQueryString('session') == 'delay') {//SSO会话续期过程启动
	setcookie("dingstudio_sso", $_COOKIE['dingstudio_sso'], time() + 3600,  "/", constant('cross_domain_config'));
	setcookie("dingstudio_ssotoken", $_COOKIE['dingstudio_ssotoken'], time() + 3600,  "/", constant('cross_domain_config'));
	echo 'success';
	exit(0);
}
else {//非法尝试直接阻断，不予外部提示
	header('Location: ./login.php?url=usercenter.php');
	exit(0);
}