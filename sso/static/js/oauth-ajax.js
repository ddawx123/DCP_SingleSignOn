
//获取url中的参数
function getUrlParam(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
	/*
	// 由于本实例中url参数将用于重定向，而某些情况下url参数会以null形式返回导致出现404问题
	// 所以这里特别修改了空值时的返回模型
	if (r != null) return unescape(r[2]); return null; //返回参数值
	*/
	if (r != null) {
		return unescape(r[2]); //返回参数值
	}
	else {
		return "/";
	}
}

//Ajax通行证登录过程（CAS同父域模式）
function ajaxSSOLogin_caslogin() {
	if ($('#username').val() == '' || $('#userpwd').val() == '') {
		Materialize.toast('亲，请输全用户帐号和密码后再试。', 3000);
		return false;
	}
	$.ajax({
		url:'./api.php?format=json&action=login',
		type:'post',
		dataType: 'json',
		data: {
			cors_domain: window.location.protocol + '//' + window.location.host,
			username: $('#username').val(),
			userpwd: $('#userpwd').val()
		},
		async: false,
		success: function (ResponseText) {
			if (ResponseText.code == 200) {
				$('#userpwd').val("");
				$('#login-page').hide();
				Materialize.toast('登录成功，请稍候！系统将自动跳转。', 2000,'',function(){
					document.location = getUrlParam('returnUrl');
				});
			}
			else if (ResponseText.code == 403) {
				$('#userpwd').val("");
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
