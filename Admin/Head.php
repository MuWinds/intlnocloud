<?php
// +----------------------------------------------------------------------
// | Quotes [ 只为给用户更好的体验]**[我知道发出来有人会盗用,但请您留版权]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 零度            盗用不留版权,你就不配拿去!
// +----------------------------------------------------------------------
// | Date: 2019年08月20日
// +----------------------------------------------------------------------
include("../Core/Common.php");
if($islogin_admin==1){}else exit("<script language='javascript'>window.location.href='./Login.php';</script>");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charSet="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title?> | <?=$conf['sitename']?>-<?=$conf['title']?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="keywords" content="<?php echo $conf['keywords']?>">
	<meta name="description" content="<?php echo $conf['description']?>">
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="/Core/Assets/Css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <script src="//cdn.staticfile.org/jquery/2.1.4/jquery.min.js"></script>
  <script src="//cdn.staticfile.org/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="/Core/Assets/Layer/layer.js"></script>
		<link rel="shortcut icon" href="/Core/Assets/Icon/favicon.ico" type="image/x-icon" />
  <!--[if lt IE 9]>
    <script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
    <script src="http://libs.useso.com/js/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>  
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">导航按钮</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="./">INTL</a>
      </div><!-- /.navbar-header -->
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="<?php echo checkIfActive('index,')?>">
            <a href="./"><span class="glyphicon glyphicon-home"></span> 首页</a>
          </li>
          <li class="<?php echo checkIfActive('Order')?>">
            <a href="./Order.php"><span class="glyphicon glyphicon-list"></span> 订单记录</a>
          </li>
          <li class="<?php echo checkIfActive('Qrlist')?>">
            <a href="./Qrlist.php"><span class="glyphicon glyphicon-qrcode"></span> 收款二维码</a>
          </li>
		<!--li><a href="./Cron.php"><span class="glyphicon glyphicon-gift"></span>监控说明</a></li>
          <li class="<?php echo checkIfActive('App')?>">
            <a href="./Wechat.php"><i class="fa fa-wechat fa-lg" style="color: green"></i></span> 微信免挂</a>
          </li>
          <li class="<?php echo checkIfActive('Work')?>">
            <a href="./Work.php"><span class="glyphicon glyphicon-globe"></span> 工单</a>
          </li>
          <li class="<?php echo checkIfActive('User')?>">
            <a href="./User.php"><span class="glyphicon glyphicon-user"></span> 用户</a>
          </li-->
		  <li class="<?php echo checkIfActive('Wechat_Trumpet,Wechat_List')?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wechat fa-lg"></i> 微信免挂<b class="caret"></b></a>
            <ul class="dropdown-menu">
			  <li><a href="./Wechat_Trumpet.php">微信免挂小号设置</a><li>
			  <li><a href="./Wechat_List.php">微信登录绑定列表</a><li>
            </ul>
          </li>
		  <li class="<?php echo checkIfActive('User,Work')?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> 用户/工单<b class="caret"></b></a>
            <ul class="dropdown-menu">
			  <li><a href="./User.php">用户管理</a><li>
			  <li><a href="./Work.php">工单系统</a><li>
            </ul>
          </li>
		  <li class="<?php echo checkIfActive('Set','Pay')?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> 配置<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="./Set.php?mod=site">网站信息配置</a></li>
              <li><a href="./Set.php?mod=pay_vip">免挂会员价格</a></li>
              <li><a href="./Set.php?mod=pay">平台支付设置</a></li>
			  <li><a href="./Set.php?mod=oauth">快捷登录配置</a><li>
			  <li><a href="./Notice.php">网站公告配置</a></li>
			  <li><a href="./Set.php?mod=template">首页模板配置</a><li>
			  <li><a href="./Set.php?mod=mail">邮箱与短信配置</a><li>
			  <li><a href="./Set.php?mod=upimg">网站Logo上传</a><li>
			  <li><a href="./Set.php?mod=cron">加快回调监控</a><li>
            </ul>
          </li>
		  <li class="<?php echo checkIfActive('Notify,Risk,Log,Clean')?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cube"></i> 其他<b class="caret"></b></a>
            <ul class="dropdown-menu">
			  <li><a href="./Notify.php">收款记录</a><li>
			  <li><a href="./Risk.php">风控记录</a><li>
			  <li><a href="./Log.php">操作日志</a><li>
			  <li><a href="./Clean.php">数据清理</a><li>
            </ul>
          </li>
		  <li class="<?php echo checkIfActive('Update,Pay')?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cloud"></i> 云端/插件/更新<b class="caret"></b></a>
            <ul class="dropdown-menu">
			  <li><a href="./Update.php">版本更新</a><li>
			  <li><a href="./Pay.php">云端对接</a><li>
			  <li><a href="./Pay_Plug-in.php">云端插件</a><li>
			  <li><a href="./Login.php?logout">退出登录</a><li>
            </ul>
          </li>
			<!--li><a href="./Pay.php"><span class="glyphicon glyphicon-cloud"></span>云端*</a></li>
			<li><a href="./Update.php"><span class="glyphicon glyphicon-check"></span>更新</a></li>
          <li class="<?php echo checkIfActive('Php_Sdk')?>">
            <a href="../User/plug-in/Php_Sdk.zip"><span class="glyphicon glyphicon-cloud-download"></span> SDK下载</a>
          </li>
          <li><a href="./Login.php?logout"><span class="glyphicon glyphicon-log-out"></span> 退出</a></li-->
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
  </nav><!-- /.navbar -->
  <div class="container" style="padding-top:60px;">
    <div class="col-xs-22 col-sm-22 col-lg-18 center-block" style="float: none;">
                                            <ol class="breadcrumb hide-phone p-0 m-0">
                                                <li class="breadcrumb-item"><a href="./">控制台</a></li>
                                                <li class="breadcrumb-item active"><?php echo $title?></li>
                                            </ol>