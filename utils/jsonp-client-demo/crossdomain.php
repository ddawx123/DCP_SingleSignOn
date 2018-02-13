<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json; Charset=UTF-8');
    if (!session_id()) { //按需全局启用Session机制
        session_start();
    }
    if (!isset($_REQUEST['username']) || !isset($_REQUEST['usermail']) || !isset($_REQUEST['newtoken'])) {
        die('{"status":false}'); //没有传入用户名、邮箱和临时token
    }
    else {
        //这里需要注意，临时token在请求完成后也会立即销毁。
        //但是系统会返回给后端一个新的token，这里仅仅做了session、cookie存储，建议妥善保存
        //该token可以实现用户信息的后续更新操作（只读权限）
        $user = file_get_contents('https://passport.dingstudio.cn/api?format=json&action=verify&token='.htmlspecialchars($_REQUEST['newtoken']).'&reqtime='.sha1(date('YmdHis',time())));
        $userinfo = json_decode($user);
        if ($userinfo->data->username != null && $userinfo->data->newtoken != null) {
            setcookie('myalbum_token', $userinfo->data->newtoken, time() + 1800, '/', $_SERVER['HTTP_HOST']);
            $_SESSION['myalbum_user'] = $userinfo->data->username;
            $_SESSION['myalbum_token'] = $userinfo->data->newtoken;
            $_SESSION['myalbum_email'] = $userinfo->data->usermail;
            die('{"status":true}'); //SSO二次核验通过，返回成功登录码
        }
        else {
            die('{"status":false}'); //SSO二次核验失败
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DingStudio SSO Platform</title>
    <script type="text/javascript" src="http://static.album.dingstudio.cn/jquery-3.3.1.js"></script>
    <script>
        var SSOClient = window.location.href;
        var SSORoot = 'https://passport.dingstudio.cn/sso/';
        var LoginServlet = SSORoot + 'login?mod=caslogin&returnUrl=' + encodeURIComponent(SSOClient);
        var VerifyServlet = 'https://passport.dingstudio.cn/api?format=json&action=verify';
        var QueryServlet = 'https://passport.dingstudio.cn/api?format=json&action=status';
        var AppUrl = '../admin.php';
    </script>
</head>
<body>
<h2>用户互联登录授权</h2>
<h3>状态：<small><span id="statusBox">正在等待认证服务器响应</span></small></h3>
<script>
    //第一次请求：通过访问SSO登录状态查询接口，获取一次性token
    $.ajax({
        url: QueryServlet,
        method: 'get',
        data: {
            'hostname': window.location.hostname,
            'requests': Date.parse(new Date()) / 1000
        },
        dataType: 'jsonp',
        jsonp: 'callback',
        success: function (response) {
            if (response.data.authcode === 1) { //判断服务端返回的二维数组data中的authcode，值为1则已登陆，继而获取一次性token
                var usertoken = response.data.token;
                //第二次请求：使用一次性token请求客户验证接口换取用户信息，一旦请求成功，在返回数据的同时该token立即失效
                $.ajax({
                    url: VerifyServlet,
                    method: 'get',
                    data: {
                        'token': usertoken,
                        'reqtime': Date.parse(new Date()) / 1000
                    },
                    dataType: 'jsonp',
                    jsonp: 'callback',
                    success: function (response) {
                        //判断服务端返回的code，若为200则说明一次性token验证通过并已销毁，继而获取二维数组data中的信息
                        if (response.code === 200) {
                            var username = response.data.username; //获取到用户账号
                            var usermail = response.data.usermail; //获取绑定邮箱号
                            var newtoken = response.data.newtoken; //获取临时token
                            $('#statusBox').css('color', 'green'); //设置界面样式为初步登录通过
                            $('#statusBox').html('<br>\
                            ' + username + '，欢迎回来。<br>\
                            您的邮箱：' + usermail + '<br>\
                            正在为您同步用户信息并自动登录，请稍候。'); //设置提示语，同时启动二次验证
                            //第三次请求：以POST方式请求本应用后端，并传入上面换取的用户信息进行二次联合验证
                            $.ajax({
                                url: SSOClient,
                                method: 'post',
                                data: {
                                    'username': username,
                                    'usermail': usermail,
                                    'newtoken': newtoken
                                },
                                dataType: 'json',
                                async: true,
                                success: function (response) {
                                    if (response.status === true) {
                                        location.href = AppUrl; //应用后端验证通过后执行跳转进入业务系统
                                    }
                                    else {
                                        //二次验证失败，但可以换取用户信息，则说明当前用户账号虽然存在但被禁止登录目标应用，给出提示
                                        setTimeout(() => {
                                            $('#statusBox').css('color', 'blue');
                                            $('#statusBox').html('<br>\
                                            ' + username + '，欢迎回来。<br>\
                                            您的邮箱：' + usermail + '<br>\
                                            <br>操作结果：您的账号没有权限进入该应用。');
                                        }, 1000);
                                    }
                                },
                                error: function (data) {
                                    //后端通讯失败，按无权登录类型处理
                                    setTimeout(() => {
                                        $('#statusBox').css('color', 'blue');
                                        $('#statusBox').html('<br>\
                                        ' + username + '，欢迎回来。<br>\
                                        您的邮箱：' + usermail + '<br>\
                                        <br>操作结果：您的账号没有权限进入该应用。');
                                    }, 1000);
                                }
                            });
                        }
                        else {
                            //使用一次性token换取用户信息时被服务端拒绝，则按未登录处理（这里为了区别完全没有任何一次性token的访客，报错提示信息颜色设置为橘红）
                            $('#statusBox').css('color', 'orangered');
                            $('#statusBox').html('会话超时，请重新登陆。正在为您跳转至统一身份认证平台');
                            setTimeout(() => {
                                location.href = LoginServlet;
                            }, 1000);
                        }
                    },
                    error: function (data) {
                        //使用一次性token换取用户信息时雨服务端通信超时，则给出网络异常的提示
                        $('#statusBox').css('color', 'red');
                        $('#statusBox').html('二次身份核验失败，此现象可能是网络不稳定所致，建议刷新重试！如果多次出现，请尝试重新登陆。');
                    }
                });
            }
            else {
                //初次验证时authcode!=0则显示此信息
                $('#statusBox').css('color', 'red');
                $('#statusBox').html('未登录或由于会话过期，正在为您跳转至统一身份认证平台');
                setTimeout(() => {
                    location.href = LoginServlet;
                }, 1000);
            }
        },
        error: function (data) {
            //无法与sso认证服务器取得任何联系则显示此信息
            $('#statusBox').css('color', 'red');
            $('#statusBox').html('无法与统一身份认证服务器正常通信，请联系认证域管理员确认是否正确接入！');
        }
    })
</script>
</body>
</html>