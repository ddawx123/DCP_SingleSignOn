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
            <li><a href="./usercenter.php">概述</a></li>
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
          <h1 class="page-header">账户设置</h1>
          <h2 id="pwdchange" name="pwdchange" class="sub-header">修改通行证密码</h2>
          <div class="table-responsive">
            <form role="form" method="post" action="./member.php?action=dochangepwd&t=<?php echo time(); ?>">
				<div class="form-group">
					<label for="oldpwd">输入旧密码</label>
					<input type="password" class="form-control" id="oldpwd" name="oldpwd" placeholder="请输入旧密码">
					<label for="newpwd">输入新密码</label>
					<input type="password" class="form-control" id="newpwd" name="newpwd" placeholder="请输入新密码">
					<label for="newpwd2">再次输入新密码</label>
					<input type="password" class="form-control" id="newpwd2" name="newpwd2" placeholder="请输入新密码">
				</div>
				<input class="btn btn-info" id="btnChange" name="btnChange" type="submit" value="提交更改">
			</form>
          </div>
		  <h2 id="userinfochange" name="userinfochange" class="sub-header">修改账户头像</h2>
		  <div class="form-group">
				<label for="inputfile">选择图片</label>
				<input type="file" id="inputfile" name="inputfile">
				<p class="help-block">*本功能尚未开发完成，暂不开放！</p>
		  </div>
		  <div class="form-group">
				<label for="email">电子邮箱</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="输入电子邮件">
				<label for="mobile">手机号码</label>
				<input type="text" class="form-control" id="mobile" name="mobile" placeholder="输入手机号">
				<label for="qqnum">QQ号码</label>
				<input type="text" class="form-control" id="qqnum" name="qqnum" placeholder="输入QQ号">
				<label for="weibo">个人微博</label>
				<input type="text" class="form-control" id="weibo" name="weibo" placeholder="输入微博网址">
				<p class="help-block">*本功能尚未开发完成，暂不开放！</p>
		  </div>
		  <h2 class="sub-header">账户增强保护</h2>
		  <label>二步验证-MFA安全保护</label>
		  <div class="checkbox">
			<label><input type="checkbox"> 启用</label>
		  </div>
		  <p class="help-block">*本功能尚未开发完成，暂不开放！</p>
		  <h2 class="sub-header">删除账户（销户）</h2>
		  <p class="help-block">*注意：一般本功能在极少数情况下才会使用，如果您执行本操作，您所有第三方应用均会删除与本账户关联的数据！*本功能尚未开发完成，暂不开放！</p>
		  <input class="btn btn-danger" id="btnChange" name="btnChange" type="button" value="删除账户和所有资料" onclick="alert('本功能尚未开发完成，暂不开放！');return false;">
        </div>
      </div>
    </div>
	
   <div id="footer" align="center" style="position: fixed;bottom: 0;left: 0;height: 20px;width: 100%;background-color:#B7DEFF">&copy;2017 Powered By DingStudio</div>
   
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
