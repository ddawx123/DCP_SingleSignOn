<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="统一身份认证平台|小丁工作室">
<meta name="author" content="DingStudio">
<meta name="generator" content="DingStudio_SSOLoginUI" />
<meta name="theme-color" content="#FFFFFF">
<title>找回密码|统一身份认证平台</title>
<script>
	function updateImage() {
		document.getElementById("authcode").src='./checkcode.php?from=findpwd&t='+Math.random();//update auth code
	}
</script>
</head>

<body>
<h1 align="center">密码重置系统</h1>
<div id="loginbox" align="center">
	<form action="./member.php?action=findpwd" method="post">
		<label for="email">邮&nbsp;&nbsp;箱：</label>
		<input name="email" id="email" type="text" placeholder="在此键入邮箱">
		<br>
		<label for="imgcode">验证码：</label>
		<input type="text" name="imgcode" id="imgcode" placeholder="在此键入验证码" maxlength="5" />
		<br>
		<img src="./checkcode.php?from=findpwd" name="authcode" id="authcode" alt="验证码" />&nbsp;
		<a href="javascript:void(0);" onclick="updateImage();">换一张</a>
		<p><input name="btnFindPwd" id="btnFindPwd" type="submit" value="提交申请">&nbsp;<input name="btnReset" id="btnReset" type="reset" value="重置"></p>
	</form>
</div>
<div id="footer" align="center"><p>小丁工作室&nbsp;版权所有&nbsp;&copy;2017 DingStudio</p></div>
</body>
</html>