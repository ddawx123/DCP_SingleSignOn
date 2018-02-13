<!DOCTYPE HTML>
<html>
	<head>
		<title>账户注册|统一身份认证平台</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="static/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="static/css/register_v2.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="static/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="static/css/ie9.css" /><![endif]-->
		<script>
			function updateImage() {
				document.getElementById("authcode").src='./checkcode.php?from=register&t='+Math.random();//update auth code
			}
		</script>
		<script src="static/js/jquery.min.js"></script>
	</head>
	<body oncontextmenu="window.event.returnValue=false" onselectstart="return false">
		<!-- Fourth -->
			<section id="fourth" class="main">
				<!--<header>
					<div class="container">
						<h2><strong>账户注册系统</strong></h2>
						<p>您正在申请统一认证账号，注册成功后您可在<br/>本主域名下使用此通行证自动登录其他应用。</p>
					</div>
				</header>-->
				<div class="content style4 featured">
					<div class="container 75%">
						<form id="form" method="post" action="./member.php?action=doregister">
							<div class="12u 12u(mobile)">
								<br/>
									<h2 style="font-size: 50px"><strong>账户注册系统</strong></h2>
									<p>一个帐号，通行所有小丁工作室旗下网站</p>
								<br/>
							</div>
							<div class="row 50%">
									<div class="3u 0u(mobile)"></div>
									<div class="6u 12u(mobile)"><input id="newuser" name="newuser" max="20" type="text" placeholder="用户名" /></div>
									<div class="3u 0u(mobile)"></div>
								<div class="12u 0u(mobile)"></div>
									<div class="3u 0u(mobile)"></div>
									<div class="6u 12u(mobile)"><input id="newpwd" name="newpwd" max="20" type="password" placeholder="密码" /></div>
									<div class="3u 0u(mobile)"></div>
								<div class="12u 0u(mobile)"></div>
									<div class="3u 0u(mobile)"></div>
									<div class="6u 12u(mobile)"><input id="email" name="email" max="30" type="text" placeholder="邮箱地址" /></div>
									<div class="3u 0u(mobile)"></div>
								<div class="12u 0u(mobile)"></div>
							</div>
							<div class="row 50%">
								<div class="3u 0u(mobile)"></div>
								<div class="6u 12u(mobile)">
									<input type="text" name="imgcode" id="imgcode" placeholder="在此键入验证码" maxlength="5" />							
								</div>
								<div class="3u 0u(mobile)"></div>
								<div class="12u">
									<img src="./checkcode.php?from=register" name="authcode" id="authcode" alt="验证码" />&nbsp;
									<a href="javascript:void(0);" onclick="updateImage();">换一张</a>
								</div>
								<!-- Geetest Operate Authority Div Container Area Start -->
								<div id="embed-captcha"></div>
    							<p id="wait" class="hide">正在加载验证码......</p>
    							<p id="notice" class="hide">请先完成验证</p>
								<!-- Geetest Operate Authority Div Container Area End -->
							</div>
							<div class="row">
								<div class="12u">
									<ul class="actions">
										<li><input type="button" id="btn" class="button" value="立即注册" /></li>
										<li><input type="reset" id="btnReset" class="button alt" value="清空输入" /></li>
									</ul>
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>

		<!-- Footer -->
			<section id="footer">
				<ul class="icons">
					<li><a href="javascript:void(0);" onClick="goTW();" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="javascript:void(0);" onClick="goFC();" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
					<li><a href="javascript:void(0);" onClick="disShowDialog();" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
					<li><a href="javascript:void(0);" onClick="disShowDialog();" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
					<li><a href="javascript:void(0);" onClick="goGT();" class="icon fa-github"><span class="label">GitHub</span></a></li>
				</ul>
				<div class="copyright">
					<ul class="menu">
						<li>&copy;Copyright 2012-<?php echo date('Y',time()); ?>.</li><li>Designed by: DingStudio</li>
					</ul>
				</div>
			</section>

		<!-- Scripts -->
			<!-- Include Geetest Authority JS Support -->
			<script src="static/js/gt.js"></script>
			<!-- Include Geetest Authority User Configure JS Support -->
			<script src="static/js/gt_userconfig.js"></script>
			<!-- Import JQuery Scrolly Plugin Support File -->
			<script src="static/js/jquery.scrolly.min.js"></script>
			<!-- Import Other Theme Javascript File -->
			<script src="static/js/skel.min.js"></script>
			<script src="static/js/util.js"></script>
			<!--[if lte IE 8]><script src="static/js/ie/respond.min.js"></script><![endif]-->
			<script src="static/js/main.js"></script>

	</body>
</html>