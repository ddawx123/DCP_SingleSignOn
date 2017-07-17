<?php
$dbhandle = MySQLInstance::getInstance();
$dbhandle = $dbhandle->connect();
$result = $dbhandle->query("select * from users where username='{$_COOKIE['dingstudio_sso']}'");
$result = $result->fetch_array();
if (!$result or $result['lastIP'] == '' or $result['lastOPTime'] == '') {
  $result = array(
    'lastIP'  =>  '暂无统计',
    'lastOPTime'  =>  date('Y/m/d h:i:s')
  );
}
$dbhandle->close();
?>
<!DOCTYPE html>
<html lang="zh">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="统一身份认证平台|用户中心">
    <meta name="author" content="DingStudio">
    <link rel="icon" href="favicon.ico">
    <title>用户中心</title>
    <!-- Bootstrap core CSS -->
    <link href="static/css/bootstrap.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="static/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="static/css/dashboard.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="static/js/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="static/js/html5shiv.min.js"></script>
      <script src="static/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./usercenter.php">用户中心|统一身份认证平台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
			<li><a>欢迎<?php echo $_COOKIE["dingstudio_sso"]; ?></a></li>
            <li><a href="./usercenter.php">首页</a></li>
            <li><a href="./usercenter.php?action=account-config#userinfochange">个人信息</a></li>
            <li><a href="./usercenter.php?action=account-config#pwdchange">账户设置</a></li>
            <li><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal">退出</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="寻找您想要的内容">
          </form>
        </div>
      </div>
    </nav>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					会话操作确认
				</h4>
			</div>
			<div class="modal-body">
				您好，名为：<?php echo $_COOKIE["dingstudio_sso"]; ?> 的用户。您确认要注销本次SSO会话吗？成功注销后使用此帐户通行证同步登录的某些应用也会自动注销，请确认没有正在进行且尚未保存的工作哦～
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-warning" onclick="document.location='./login.php?action=dologout&url=login.php';">仍要注销</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
<!-- 模态框结束 -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="active"><a href="./usercenter.php">概述 <span class="sr-only">(current)</span></a></li>
            <li><a href="./usercenter.php?action=notification">通知中心</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="./usercenter.php?action=app-manager">授权的应用</a></li>
            <li><a href="./usercenter.php?action=oauth-config">第三方绑定</a></li>
            <li><a href="./usercenter.php?action=devtool">开发者通道</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="./login.php?action=dologout&url=login.php">退出登录</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">用户中心后台首页</h1>
          <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
<h4>开发中</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
<h4>开发中</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
<h4>开发中</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
<h4>开发中</h4>
              <span class="text-muted">Something else</span>
            </div>
          </div>
          <h2 class="sub-header">最后一次活动</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>事件ID</th>
                  <th>IP地址</th>
                  <th>登录方式</th>
                  <th>应用名称</th>
                  <th>登录时间</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $securityInfo = explode(',',$result['lastIP']);
              $userOPTime = explode(',',$result['lastOPTime']);
              for ($index = 0; $index < count($securityInfo); $index++) {
                echo '
                <tr>
                <td>'.$index.'</td>
                <td>'.$securityInfo[$index].'</td>
                <td>B/S应用程序</td>
                <td>统一身份认证</td>
                <td>'.$userOPTime[$index].'</td>
                ';
              }
              ?>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
	<div id="footer" align="center" style="position: fixed;bottom: 0;left: 0;height: 20px;width: 100%;background-color:#B7DEFF">Powered By DingStudio</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="static/js/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="static/js/jquery.min.js"><\/script>')</script>
    <script src="static/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="static/js/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="static/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
