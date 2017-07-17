<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <title>统一身份认证平台|小丁工作室</title>
    <link rel="stylesheet" type="text/css" href="static/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="static/css/oauth.css">
    <link rel="stylesheet" href="static/css/materialize.min.css">
    <style type="text/css">
        html,
        body {
            height: 100%;
        }

        html {
            display: table;
            margin: auto;
        }

        body {
            display: table-cell;
            vertical-align: middle;
        }

        .margin {
            margin: 0 !important;
        }
    </style>
    <!--[if IE]>
        <script src="static/js/html5.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="static/js/jquery.js"></script>
    <script type="text/javascript" src="static/js/oauth-ajax.js"></script>
</head>
<body class="bgcolor-custom">
    <div id="login-page" class="row">
        <div class="col s12 z-depth-6 card-panel">
            <form class="login-form" method="post" action="" onsubmit="return ajaxSSOLogin_caslogin()">
                <div class="row">
                    <div class="input-field col s12 center">
                        <img src="static/images/oauth_logo.png" alt="" class="responsive-img valign profile-image-login">
                        <p class="center login-form-text">使用 <b>小丁工作室-统一身份认证平台</b> 登录该网站<br><small>您正在使用互联登录模式访问您的网站</small></p>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <input class="validate" name="username" id="username" type="text">
                        <label for="username">用户帐号</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <input class="validate" name="userpwd" id="userpwd" type="password">
                        <label for="userpwd">用户密码</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button type="submit" class="btn waves-effect waves-light col s12">登　录</button>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 m6 l6">
                        <p class="margin medium-small"><a href="./login.php?mod=register">现在注册</a></p>
                    </div>
                    <div class="input-field col s6 m6 l6">
                        <p class="margin right-align medium-small"><a href="./login.php?mod=findpassword">忘记密码?</a></p>
                    </div>
            </form>
        </div>
    </div>
    </div>
    <div id="footer" align="center" style="filter: alpha(Opacity=80);-moz-opacity: 0.5;opacity: 0.5;position: fixed;bottom: 0;left: 0;height: 20px;width: 100%;background-color: #000000;color: #ffffff">Powered By <a href="http://www.dingstudio.cn/?ref=passport" target="_blank">DingStudio(小丁工作室)</a> &copy;Copyright 2016-<?php echo date('Y',time()); ?> <a href="./login.php?mod=caslogin_v1" target="_self">返回旧版</a></div>
    <!--materialize js-->
    <script src="static/js/materialize.js"></script>
</body>
</html>