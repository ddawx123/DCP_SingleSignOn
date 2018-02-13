<?php
/**
* SSO会话续期模块，默认SSO会话有效期为1小时。
* 通过用户在1小时内访问业务应用，业务应用后端后向本文件发起请求实现会话自动续期。
* 如果用户在1小时内没有访问任何与本SSO统一身份认证系统相关联的业务应用，会话将会在认证起始时间的1小时后失效。
* 失效后用户访问各个接入本SSO统一身份认证系统的网站需要重新走SSO统一登录认证流程。。。
* 业务应用续期调用方法（如通过file_get_contents调用获得返回值）：renewal.php?session=delay
* 必须传入session参数值为delay，且必须在会话尚未过期的时段方可调用，其他未授权对象的调用会被全部重定向到登录页面。
* Copyright 2017 DingStudio All Right Reserved
*/

header("Content-Type: text/html; charset=UTF-8");
include(dirname(__FILE__).'/function/apptype.class.php');
if (!file_exists(dirname(__FILE__)."/function/config.php") && !file_exists(dirname(__FILE__)."/function/core.php")) {
	die("抱歉，我们没有找到某些系统文件。请检查系统文件完整性！");
}
require_once(dirname(__FILE__)."/function/core.php");
if(CoreServlet::SSOCheckExist("0")=="authed") {//SSO会话续期过程启动
	if (ToolServlet::GetQueryString('session') == 'delay') {
		setcookie("dingstudio_sso", $_COOKIE['dingstudio_sso'], time() + 3600,  "/", constant('cross_domain_config'));
		setcookie("dingstudio_ssotoken", date('YmdHis',time()), time() + 3600,  "/", constant('cross_domain_config'));
		if (ToolServlet::GetQueryString('returnUrl') != '') {
			header('Location: '.ToolServlet::GetQueryString('returnUrl'));
		}
		else {
			echo '会话续期成功！';
		}
	}
	else if (ToolServlet::GetQueryString('session') == 'relogin') {
		require_once(dirname(__FILE__)."/template/caslogin_ui_renewal.php"); //引入同父域续登UI
	}
	else {
		header('Location: ./usercenter.php');
	}
}
else if (ToolServlet::GetQueryString('returnUrl') != '') {
	//require_once(dirname(__FILE__)."/template/caslogin_ui_renewal.php"); //引入同父域续登UI
	header('Location: ./login.php?returnUrl='.urlencode(ToolServlet::GetQueryString('returnUrl')));
}
else {//非法尝试直接阻断，不予外部提示
	header('Location: ./usercenter.php');
	exit(0);
}