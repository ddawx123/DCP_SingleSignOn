<?php
header("Content-Type: text/html; charset=UTF-8");
if (!file_exists(dirname(__FILE__)."/function/config.php") && !file_exists(dirname(__FILE__)."/function/core.php")) {//检查系统文件
	die("抱歉，我们没有找到某些系统文件。请检查系统文件完整性！");
}
require_once(dirname(__FILE__)."/function/core.php");//引入SSO核心类库

//检查是否已存在合法的登陆会话以确定是否给予自动登陆
if (CoreServlet::SSOCheckExist("0")=="authed" && ToolServlet::GetQueryString('action') != 'dologout' && ToolServlet::GetQueryString('url') != '') {
	header('Location: '.ToolServlet::GetQueryString('url'));
	exit(0);
}
else if (CoreServlet::SSOCheckExist("0")=="authed" && ToolServlet::GetQueryString('action') != 'dologout') {
	header('Location: ./usercenter.php');
	exit(0);
}
else {
	if (isset($_POST['username']) && isset($_POST['userpwd']) && ToolServlet::GetQueryString('action') == 'dologin') {//执行用户SSO登陆过程
		$username = $_POST['username'];//传入账号POST值到变量
		$password = md5($_POST['userpwd']);//传入密码POST值到变量
		if (CoreServlet::SSOCheck($username, $password)=='authed' && ToolServlet::GetQueryString('url') != '') {//SSO开始登录认证
			header('Location: '.ToolServlet::GetQueryString('url'));
			exit(0);
		}
		else if (CoreServlet::SSOCheck($username, $password)=='authed' && ToolServlet::GetQueryString('url') == '') {//成功登录但没有传递回调地址时的默认动作
			header('Location: ./usercenter.php');
			exit(0);
		}
		else if (CoreServlet::SSOCheck($username, $password)=='error-654') {//后端服务器异常处理
			die('<script>alert("后端程序无法与数据库建立连接，请报告错误码给网站管理员。错误码：654，错误类型：MySQL_Connect_Error。");history.go(-1);');
		}
		else {//SSO认证失败的回调处理
			die('<script>alert("非法的账号或密码，请核实后重试！");history.go(-1);</script>');
		}
	}
	else if (ToolServlet::GetQueryString('action') == 'dologout') {
		CoreServlet::SSOLogout(ToolServlet::GetQueryString('url'));//执行注销过程并传入回调URL
	}
	else {
		switch (ToolServlet::GetQueryString('mod')) {
			case "crossdomain":
			//引入跨域登录UI
			break;
			case "crossdomain_nossl":
			//引入跨域登录UI(NOSSL模式)
			break;
			case "caslogin":
			require_once(dirname(__FILE__)."/template/caslogin_ui.php");//引入同父域登录UI
			break;
			case "register":
			require_once(dirname(__FILE__)."/template/register_ui.php");//引入注册UI
			break;
			//////////////////////////////////////////////////////////////////////
			/* 测试版用户注册界面接入点开始 */
			//////////////////////////////////////////////////////////////////////
			case "register_v2":
			require_once(dirname(__FILE__)."/template/register_ui_v2.php");//引入注册UI_V2测试版
			break;
			//////////////////////////////////////////////////////////////////////
			/* 测试版用户注册界面接入点结束 */
			//////////////////////////////////////////////////////////////////////
			case "findpassword":
			require_once(dirname(__FILE__)."/template/findpwd_ui.php");//引入密码找回UI
			break;
			case "resetpwd":
			session_start();
			if (!isset($_GET['email']) or !isset($_GET['token']) or $_GET['email'] == '' or $_GET['token'] == '' or !isset($_SESSION['dingstudio_sso_findpwd']) or !isset($_SESSION['dingstudio_sso_usrfind']) or $_SESSION['dingstudio_sso_findpwd'] == '' or $_SESSION['dingstudio_sso_usrfind'] == '') {
				header("refresh:5; url=http://".$_SERVER['HTTP_HOST']);
				echo "哎呀，该密码重置链接已经失效了。请重新前往密码重置系统再次提交申请！本页面将在5秒后跳转。";
			}
			else {
				$email = $_GET['email'];
				$gtoken = $_GET['token'];
				$stoken = $_SESSION['dingstudio_sso_findpwd'];
				$user = $_SESSION['dingstudio_sso_usrfind'];
				if ($gtoken != $stoken) {
					header("refresh:5; url=http://".$_SERVER['HTTP_HOST']);
					echo "哎呀，该密码重置链接已经失效了。请重新前往密码重置系统再次提交申请！本页面将在5秒后跳转。";
				}
				else {
					$sqlconn = MySQLInstance::getInstance()->connect();//通过单例方式建立MySQL数据库连接
					if ($sqlconn->connect_error) {
						die('后端控制器返回异常，无法建立数据库连接！请联系管理员解决此问题。');
					}
					$sqlcode = "update users set password='".md5('1234567890')."' where username='{$user}'";//重置密码到数据库
					$result = $sqlconn->query($sqlcode);
					MySQLInstance::getInstance()->disconnect();//关闭数据库连接
					header("refresh:5; url=http://".$_SERVER['HTTP_HOST']);
					echo "恭喜您，帐户：".$user." 的密码已被重置为：1234567890。请立即登录您的帐户并修改密码！本页面将在5秒后跳转。";
					unset($_SESSION['dingstudio_sso_findpwd']);
					unset($_SESSION['dingstudio_sso_usrfind']);
					session_destroy();
				}
			}
			break;
			case "caslogin_v1":
			require_once(dirname(__FILE__)."/template/login_ui.php");//引入登录UI_V1
			break;
			default:
			require_once(dirname(__FILE__)."/template/caslogin_ui.php");//引入同父域登录UI
		}
	}
}
?>
