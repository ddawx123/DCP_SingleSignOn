<?php
header("Content-Type: text/html; charset=UTF-8");
if (!file_exists(dirname(__FILE__)."/function/config.php") && !file_exists(dirname(__FILE__)."/function/core.php")) {
	die("抱歉，我们没有找到某些系统文件。请检查系统文件完整性！");
}
require_once(dirname(__FILE__)."/function/core.php");//引入SSO核心类库

//启动安全检测//
require_once(dirname(__FILE__)."/function/security.class.php");//引入安全防护模块
$secapp = new Security();
$secapp->checkDomain();
$secapp->create_CSRFToken();
//安全检测结束//

//检查是否已存在合法的登陆会话
if (CoreServlet::SSOCheckExist("0")=="authed" && ToolServlet::GetQueryString('action') == 'doregister') {
	header('Location: ./usercenter.php');//已登录，阻止继续注册，并跳转到用户中心
	exit(0);
}
if (isset($_POST['newuser']) && isset($_POST['newpwd']) && isset($_POST['email']) && isset($_POST['imgcode']) && ToolServlet::GetQueryString('action') == 'doregister') {//执行用户注册过程
	if (constant('register_allowed')=='false') {//config.php注册封禁选项读取应用
		die('<script>alert("非常抱歉，注册功能目前处于物理封禁状态。请联系管理员解决此问题！");document.location="./login.php";</script>');
	}
	session_start();
	if ($_POST['imgcode'] != $_SESSION["dingstudio_sso_authcode"]) {
		session_destroy();//销毁原图形验证session
		die('<script>alert("无效的图形验证码，请重试！");document.location="./login.php?mod=register";</script>');
	}
	else {
		session_destroy();//销毁原图形验证session
		$sqlconn = MySQLInstance::getInstance()->connect();
		if ($sqlconn->connect_error) {
			die('后端控制器返回异常，无法建立数据库连接！请联系管理员解决此问题。');
		}
		else {
			$sqlcode = "select username from users where username='{$_POST['newuser']}'";
			$result = $sqlconn->query($sqlcode);
			$rows = mysqli_fetch_array($result);
			mysqli_free_result($result);
			if ($rows['username']==$_POST['newuser']) {
				die('<script>alert("非常抱歉，您的用户名目前已被注册！请换一个更有创意的用户名吧~");document.location="./login.php?mod=register";</script>');
			}
			if ($rows['email']==$_POST['email']) {
				die('<script>alert("非常抱歉，您的邮箱目前已被其他账号绑定！如果不是您本人的操作，请联系站长为您解绑。");document.location="./login.php?mod=register";</script>');
			}
			$sqlcode = "insert into users (username,password,usertoken,email) values ('{$_POST['newuser']}','".md5($_POST['newpwd'])."',NULL,'{$_POST['email']}')";
			$result = $sqlconn->query($sqlcode);
			if ($result) {
				MySQLInstance::getInstance()->disconnect();//关闭数据库连接
				echo '<script>alert("账户注册成功！");document.location="./login.php";</script>';
				exit(0);
			}
			else {
				MySQLInstance::getInstance()->disconnect();//关闭数据库连接
				echo '<script>alert("非常遗憾，账户注册失败！错误原因：我们无法把注册记录插入后台数据库，可能是数据库已经写满或当前已设置为只读模式。");document.location="./login.php";</script>';
				exit(0);
			}
		}
	}
}
else {
	switch (ToolServlet::GetQueryString('action')) {
		case "dochangepwd":
		if (isset($_POST['oldpwd']) && isset($_POST['newpwd']) && isset($_POST['newpwd2'])) {
			if ($_POST['newpwd'] != $_POST['newpwd2'] or $_POST['newpwd'] == '' or $_POST['newpwd2'] == '') {
				die('<script>alert("由于新密码二次校验无效导致本次密码更新失败，请重试！");history.go(-1);</script>');
			}
			$sqlconn = MySQLInstance::getInstance()->connect();//通过单例方式建立MySQL数据库连接
			if ($sqlconn->connect_error) {
				die('后端控制器返回异常，无法建立数据库连接！请联系管理员解决此问题。');
			}
			$sqlcode = "select * from users where (username='{$_COOKIE['dingstudio_sso']}') and (password='".md5($_POST['oldpwd'])."')";//查询数据库检测账户旧密码是否匹配
			$result = $sqlconn->query($sqlcode);//执行上述SQL语句
			if($result->num_rows > 0) {
				$sqlcode = "update users set password='".md5($_POST['newpwd2'])."' where username='{$_COOKIE['dingstudio_sso']}'";//更新密码到数据库
				$result = $sqlconn->query($sqlcode);
				MySQLInstance::getInstance()->disconnect();//关闭数据库连接
				die('<script>alert("密码更新完成！为了账户安全，现需退出重新登录。");document.location="./login.php?action=dologout&url=login.php";</script>');
			}
			else {
				MySQLInstance::getInstance()->disconnect();//关闭数据库连接
				die('<script>alert("由于旧密码无效导致本次密码更新失败，为了账户安全，系统将注销您本次会话！请重新登录后重试。");document.location="./login.php?action=dologout&url=login.php";</script>');
			}
		}
		else {
			die('Illegal operation');
		}
		break;
		case "findpwd":
		if (isset($_POST['email']) && isset($_POST['imgcode'])) {
			if (constant('findpwd_allowed')=='false') {//config.php注册封禁选项读取应用
				die('<script>alert("非常抱歉，管理员全局禁用了密码重置功能。请联系管理员解决此问题！");document.location="./login.php";</script>');
			}
			session_start();
			if ($_POST['imgcode'] != $_SESSION["dingstudio_sso_authcode"]) {
				session_destroy();//销毁原图形验证session
				die('<script>alert("无效的图形验证码，请重试！");document.location="./login.php?mod=findpassword";</script>');
			}
			else {
				$sqlconn = MySQLInstance::getInstance()->connect();//通过单例方式建立MySQL数据库连接
				if ($sqlconn->connect_error) {
					die('后端控制器返回异常，无法建立数据库连接！请联系管理员解决此问题。');
				}
				$email=str_replace(" ","",$_POST['email']);//取邮箱变量并去除空格
				$sqlcode = "select * from users where email='{$email}'";
				$result = $sqlconn->query($sqlcode);
				$rows = mysqli_fetch_array($result);
				mysqli_free_result($result);
				MySQLInstance::getInstance()->disconnect();//关闭数据库连接
				if ($rows['email']==$_POST['email']) {
					require_once(dirname(__FILE__)."/function/class.phpmailer.php");//引入PHPMailer类
					require_once(dirname(__FILE__)."/function/class.smtp.php");//引入SMTP类
					$username = $rows['username'];//获取邮箱对应用户名
					$applytime = date('Y年m月d日 H时i分s秒',time());
					$token = md5(date('Ymdhis',time()));
					$_SESSION['dingstudio_sso_findpwd'] = $token;
					$_SESSION['dingstudio_sso_usrfind'] = $username;
					$url_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
					$url = $url_type.$_SERVER['HTTP_HOST'].'/sso/login.php?mod=resetpwd&email='.$email.'&token='.$token;//构造密码重置URL
					$mail = new PHPMailer();
					$mail->IsSMTP();//set mailer to use SMTP
					$mail->Host = "smtp.example.cn";//SMTP Host Setup
					//$mail->SMTPSecure = "ssl";//SMTP SSL Setup
					$mail->Port = 25;//SMTP Port Setup
					$mail->SMTPAuth = true;//SMTP Authmode Setup
					$mail->Username = "accounts@example.cn";//SMTP Username Setup
					$mail->Password = "123456";//SMTP Password Setup
					$mail->From = "accounts@example.cn";//SMTP Fromaddress Setup
					$mail->FromName = "统一身份认证系统";//Mail Theme Name
					$mail->AddAddress($email, $username);//Mail To Where Address Setup
					$mail->WordWrap = 50;
					//$mail->AddAttachment("/var/tmp/file.tar.gz");//Add an attachment
					//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");//Add an attachment with a new filename
					$mail->IsHTML(true);
					$mail->Subject = "您有一封密码重置信|统一身份认证系统";
					$mail->Body = '尊敬的用户 '.$username.'：<br>您在'.$applytime.'提交了密码重置请求，请<a href="'.$url.'" target="_blank">点击此处</a>，按系统提示步骤继续进行密码重设操作。<br>如果无法点击，请复制此URL：'.$url.' 到您的浏览器访问！ <br><hr><br>密码重置程序由小丁工作室设计，版权所有。Copyright 2016-'.date('Y',time()).' All Right Reserved.';
					if (!$mail->Send()) {
						die("哇，邮件发送失败了！后端邮件服务器可能没有正确配置，请联系管理员并提供以下错误技术信息。错误技术信息定位：" . $mail->ErrorInfo);
					}
					else {
						header("refresh:5; url=".$url_type.$_SERVER['HTTP_HOST']);
						$_SESSION["dingstudio_sso_authcode"] = null;
						unset($_SESSION["dingstudio_sso_authcode"]);
						echo "恭喜亲！邮件发送成功，请到您的邮箱查看密码重置信息。注意：密码重置流程必须在10分钟内完成，逾期需重新申请！本页面将在5秒后跳转。";
					}
				}
				else {
					die('<script>alert("亲，您所键入的邮箱地址并没有与本系统中任何账号进行绑定。请更换一个有效的邮箱地址再试哦！");document.location="./login.php?mod=findpassword";</script>');
				}
			}
		}
		else {
			die('Illegal operation');
		}
		break;
		default:
		die('系统运行正常，但是您没有传入具体的操作类型！来自小丁工作室的温馨提示');
	}
}

