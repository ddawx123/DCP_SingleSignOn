<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<meta name="description" content="统一身份认证平台|小丁工作室">
		<meta name="author" content="DingStudio">
		<meta name="generator" content="DingStudio_SSOLoginUI" />
		<meta name="theme-color" content="#FFFFFF">
		<title>统一身份认证平台|小丁工作室</title>
		<!--[if lte IE 8]><script src="static/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="static/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="static/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="static/css/ie9.css" /><![endif]-->
		<style>body { color: #384452; } b { color: #384452; } </style>
		<script src="static/js/main.js" type="text/javascript"></script>
		<script type="text/javascript">
			function updateImage() {
				document.getElementById("authcode").src='./checkcode.php?from=login&t='+Math.random();;//update auth code
			}
			
			function validate_required(field,alerttxt) {
				with (field) {
					if (value==null||value=="") {
						alert(alerttxt);
						return false;
					}
					else {
						return true;
					}
				}
			}

			function validate_form(thisform) {
				with (thisform) {
					if (validate_required(username,"亲，帐户名称不能为空哦！")==false) {
						username.focus();
						return false;
					}
					else if (validate_required(userpwd,"亲，帐户密码不能为空哦！")==false) {
						userpwd.focus();
						return false;
					}
					else {
						$message._show('success', 'Thank you!');
						return true;
					}
				}
			}
		</script>
	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<h1>统一身份认证平台</h1>
				<p>您正在访问受统一身份认证平台保护的区域，成功登录后您将可以使用本通行证访问所有本主域名下的子站点。</p>
			</header>

		<!-- Signup Form -->
			<form id="signup-form" name="signup-form" method="post" action="./login.php?action=dologin&url=<?php echo ToolServlet::GetQueryString('url').'&systoken='.date('Ymdhis',time()); ?>" style="color: #ffffff;" onsubmit="return validate_form(this)">
				<input name="username" id="username" type="text" placeholder="在此键入帐户名称"><br>
				<input name="userpwd" id="userpwd" type="password" placeholder="在此键入账户密码">
				<p><input name="btnLogin" id="btnLogin" type="submit" value="登录">&nbsp;<input name="btnReset" id="btnReset" type="reset" value="重置"></p>
				
			</form>
			<div id="regtip" style="color: #ffffff;">没有账户？<a href="./login.php?mod=register" target="_self">点此注册</a></div>
			<br>
			<div id="pwdtip" style="color: #ffffff;">无法登陆？<a href="./login.php?mod=findpassword" target="_self">找回密码</a></div>
		<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="javascript:void(0);" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="javascript:void(0);" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
					<li><a href="javascript:void(0);" class="icon fa-github"><span class="label">GitHub</span></a></li>
					<li><a href="javascript:void(0);" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
				</ul>
				<ul class="copyright">
					<li>&copy; 2017.</li><li>Designed by: DingStudio</li>
				</ul>
			</footer>

		<!-- Scripts -->
			<!--[if lte IE 8]><script src="static/js/ie/respond.min.js"></script><![endif]-->
			<script src="//static.dingstudio.cn/browser-update.js"></script>

	</body>
</html>