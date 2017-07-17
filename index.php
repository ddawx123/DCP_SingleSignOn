<?php
header("Content-Type: text/html; charset=UTF-8");
header("refresh:3; url=./sso/login.php?returnUrl=".urlencode('http://'.$_SERVER['HTTP_HOST'].'/sso/usercenter.php'));
echo '正在跳转，请稍候。';