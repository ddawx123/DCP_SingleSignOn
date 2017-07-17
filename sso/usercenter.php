<?php
header("Content-Type: text/html; charset=UTF-8");
if (!file_exists(dirname(__FILE__)."/function/config.php") && !file_exists(dirname(__FILE__)."/function/core.php")) {
	die("抱歉，我们没有找到某些系统文件。请检查系统文件完整性！");
}
require_once(dirname(__FILE__)."/function/core.php");
if(CoreServlet::SSOCheckExist("0")=="authed") {//检查是否已存在合法的登陆会话以确定是否给予自动登陆
	//die("已经了通过认证。<a href='./login.php?action=dologout&url=login.php' target='_self'>点此</a>注销登录");
	switch (ToolServlet::GetQueryString('action')) {
		case "account-config":
		require_once(dirname(__FILE__)."/template/dashboard_account_ui.php");//引入帐户设置UI
		break;
		default:
		require_once(dirname(__FILE__)."/template/dashboard_ui.php");//引入Dash主面板
	}
}
else {
	header('Location: ./login.php?url=usercenter.php');
}
