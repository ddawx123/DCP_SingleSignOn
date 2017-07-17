
//获取url中的参数
function getUrlParam(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

//Ajax通行证登录过程（跨主域模式）
function ajaxSSOLogin() {
	$.ajax({
		url:'./api.php?format=ajaxlogin',
		type:'post',
		dataType: 'json',
		data: {
			username: $('#username').val(),
			userpwd: $('#userpwd').val()
		},
		async: false,
		success: function (ResponseText) {
			if (ResponseText.code == 200) {
				Materialize.toast('登录成功，请稍候！', 2000,'',function(){
					document.location="./crossdomain.php?domain=" + getUrlParam('domain') + "&url=" + getUrlParam('returnUrl');
				});
			}
			else if (ResponseText.code == 403) {
				Materialize.toast('哎呀，用户名或密码不正确。请检查后再试一次吧！', 3000);
			}
			else {
				Materialize.toast('远程服务器Ajax接口返回未知错误，请联系管理员并提供错误代码。错误码：' + code, 5000);
			}
		},
		error: function(){
			Materialize.toast('当前无法与远程服务器建立连接，请检查本地网络连接是否可用。如果一切正常，请稍后再试一次！', 5000);
		}
	});
	return false;
}

//Ajax通行证登录过程（NoSSL模式）
function ajaxSSOLogin_nossl() {
	$.ajax({
		url:'./api.php?format=ajaxlogin',
		type:'post',
		dataType: 'json',
		data: {
			username: $('#username').val(),
			userpwd: $('#userpwd').val()
		},
		async: false,
		success: function (ResponseText) {
			if (ResponseText.code == 200) {
				Materialize.toast('登录成功，请稍候！', 2000,'',function(){
					document.location="./crossdomain.php?nossl=true&domain=" + getUrlParam('domain') + "&url=" + getUrlParam('returnUrl');
				});
			}
			else if (ResponseText.code == 403) {
				Materialize.toast('哎呀，用户名或密码不正确。请检查后再试一次吧！', 3000);
			}
			else {
				Materialize.toast('远程服务器Ajax接口返回未知错误，请联系管理员并提供错误代码。错误码：' + code, 5000);
			}
		},
		error: function(){
			Materialize.toast('当前无法与远程服务器建立连接，请检查本地网络连接是否可用。如果一切正常，请稍后再试一次！', 5000);
		}
	});
	return false;
}

//Ajax通行证登录过程（CAS同父域模式）
function ajaxSSOLogin_caslogin() {
	$.ajax({
		url:'./api.php?format=ajaxlogin',
		type:'post',
		dataType: 'json',
		data: {
			username: $('#username').val(),
			userpwd: $('#userpwd').val()
		},
		async: false,
		success: function (ResponseText) {
			if (ResponseText.code == 200) {
				Materialize.toast('登录成功，请稍候！', 2000,'',function(){
					document.location=getUrlParam('returnUrl');
				});
			}
			else if (ResponseText.code == 403) {
				Materialize.toast('哎呀，用户名或密码不正确。请检查后再试一次吧！', 3000);
			}
			else {
				Materialize.toast('远程服务器Ajax接口返回未知错误，请联系管理员并提供错误代码。错误码：' + code, 5000);
			}
		},
		error: function(){
			Materialize.toast('当前无法与远程服务器建立连接，请检查本地网络连接是否可用。如果一切正常，请稍后再试一次！', 5000);
		}
	});
	return false;
}
