<?php
header('Content-Type: text/html; charset=UTF-8');
if (!file_exists(dirname(__FILE__)."/function/config.php") && !file_exists(dirname(__FILE__)."/function/core.php")) {//检查系统文件
	die("抱歉，我们没有找到某些系统文件。请检查系统文件完整性！");
}
require_once(dirname(__FILE__)."/function/core.php");//引入SSO核心类库
if (CoreServlet::SSOCheckExist("0")=="authed") {
	die('该页面可能已过期，因为您当前已经成功登录了网站通行证！请尝试刷新页面。');
}
?>
<!-- Created By DingStudio Technology -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录网站通行证</title>
<link href="static/css/iframeLoginForm.css" rel="stylesheet" type="text/css" />
<script>
	function doLoginAction() {
		if (document.getElementById('username').value == '') {
			alert('亲，请确认用户帐号填写完毕后再试！');
			document.getElementById('username').focus();
			return false;
		}
		else if (document.getElementById('password').value == '') {
			alert('亲，请确认帐号密码填写完毕后再试！');
			document.getElementById('password').focus();
			return false;
		}
		else {
			var account = document.getElementById('username').value;
			var passwd = document.getElementById('password').value;
			var xhr;
			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
    		    xhr=new XMLHttpRequest();
    		}
    		else {// code for IE6, IE5
    		    xhr=new ActiveXObject("Microsoft.XMLHTTP");
    		}
			xhr.onreadystatechange=function() {
    		    if (xhr.readyState == 4 && xhr.status == 200) {
    		        var data = eval("("+xhr.responseText+")");
            		authcode = data.code;
    		        //alert(authcode);
    		        if (authcode == 200) {
						alert('登录成功！请关闭登录窗口继续访问。');
						location.href = "javascript:document.write('您已成功登录，请返回继续浏览。');";
    		        }
    		        else {
						document.getElementById('password').value = "";
						alert('登录失败，请检查用户名或密码是否正确。');
    		        }
    		    }
    		}
    		xhr.withCredentials = true;
    		xhr.open("POST","https://passport.dingstudio.cn/sso/api?format=json&action=login",true);
    		xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    		xhr.send('username=' + account + '&userpwd=' + passwd + '&cors_domain=' + window.location.protocol + '//' + window.location.host);
			return false;
		}
	}
</script>
</head>
<body>
<form action='https://passport.dingstudio.cn/sso/login.php?action=dologin' method='post' name='fmbbsuserload' id='fmbbsuserload' onSubmit="return doLoginAction();" target="_parent">
<tr>
<td>
用户名：<input type='text' name='username' id='username' class='input1'>
密码：<input type='password' name='password' id='password' class='input1'>
</td>
</tr>
<tr>
<td height="90">
<br><br>
<input type="image" value="" src="static/images/btnLogin.gif" name="submit" align="absmiddle">
<a href='https://passport.dingstudio.cn/sso/login.php?mod=register' target='_blank'>注册</a>　
<a href='https://passport.dingstudio.cn/sso/login.php?mod=findpassword' target='_blank'>忘了密码？</a>
</td>
</tr>
</table>
</form>
<div id="footer" align="center" style="filter: alpha(Opacity=80);-moz-opacity: 0.5;opacity: 0.5;position: absolute;bottom: 0;left: 0;height: 20px;width: 100%;background-color: #000000;color: #ffffff">Powered By DingStudio</div>
</body>
</html>