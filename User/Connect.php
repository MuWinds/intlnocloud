<?php
/**
 * QQ互联
**/
include("../Core/Common.php");

if(isset($_GET['act']) && $_GET['act']=='qrlogin' && $conf['login_qq']!=1){
	if(isset($_SESSION['findpwd_qq']) && $qq=$_SESSION['findpwd_qq']){
		$user_row=$DB->query("SELECT * FROM pay_user WHERE qq='{$qq}' limit 1")->fetch();
		unset($_SESSION['findpwd_qq']);
		if($user_row){
			$pid=$user_row['pid'];
			$key=$user_row['key'];
			if($islogin_user==1){
				exit('{"code":-1,"msg":"当前QQ已绑定商户ID:'.$pid.'，请勿重复绑定！"}');
			}
			$isrow=$DB->query("SELECT * FROM pay_user WHERE user='{$user_row['user']}' limit 1")->fetch();
			if($isrow and $isrow['pass']){
				$pid = $isrow['user'];
				$key = $isrow['pass'];
			}else{
				$pid = $user_row['pid'];
				$key = $user_row['key'];
			}
			$session=md5($pid.$key.$password_hash);
			$expiretime=time()+604800;
			$token=authcode("{$pid}\t{$session}\t{$expiretime}", 'ENCODE', $conf['KEY']);
			setcookie("user_token", $token, time() + 604800);
			$result=array("code"=>0,"msg"=>"登录成功！正在跳转到用户中心","url"=>"./");
			$city=get_ip_city($ip)['Result']['Country'];
			$DB->exec("insert into `pay_log` (`pid`,`type`,`date`,`ip`,`city`) values ('".$user_row['pid']."','商户QQ扫码快捷登录','".$date."','".$ip."','".$city."')");
		}elseif($islogin_user==1){
			$result=array("code"=>0,"msg"=>"已成功登陆！","url"=>"./index.php");
		}else{
			$result=array("code"=>0,"msg"=>"系统不存在此绑定QQ,请检查重试","url"=>"./Login.php");
		}
	}else{
		$result=array("code"=>-1, "msg"=>"验证失败，请重新扫码");
	}
	exit(json_encode($result));
}elseif(isset($_GET['act']) && $_GET['act']=='qrcode'){
	$image=trim($_POST['image']);
	$result = qrcodelogin($image);
	exit(json_encode($result));
}
if($islogin2==1 && !isset($_GET['bind'])){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}else{
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>QQ扫码登录 | <?php echo $conf['sitename']?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="stylesheet" href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="//cdn.staticfile.org/animate.css/3.5.2/animate.min.css" type="text/css" />
<link rel="stylesheet" href="//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="//cdn.staticfile.org/simple-line-icons/2.4.1/css/simple-line-icons.min.css" type="text/css" />
<link rel="stylesheet" href="./assets/css/font.css" type="text/css" />
<link rel="stylesheet" href="./assets/css/app.css" type="text/css" />
<style>input:-webkit-autofill{-webkit-box-shadow:0 0 0px 1000px white inset;-webkit-text-fill-color:#333;}img.logo{width:14px;height:14px;margin:0 5px 0 3px;}</style>
</head>
<body>
<div class="app app-header-fixed  ">
<div class="container w-xxl w-auto-xs" ng-controller="SigninFormController" ng-init="app.settings.container = false;">
<span class="navbar-brand block m-t" id="sitename"><?php echo $conf['sitename']?></span>
<div class="m-b-lg">
<div class="wrapper text-center">
<strong>QQ扫码登录</strong>
</div>
<form name="form" class="form-validation">
<div class="text-danger wrapper text-center" ng-show="authError">
</div>
	<div class="form-group" style="text-align: center;">
		<div class="list-group-item list-group-item-info" style="font-weight: bold;" id="login">
			<span id="loginmsg">请使用QQ手机版扫描二维码</span><span id="loginload" style="padding-left: 10px;color: #790909;">.</span>
		</div>
		<div id="qrimg" class="list-group-item">
		</div>
		<div class="list-group-item" id="mobile" style="display:none;"><button type="button" id="mlogin" onclick="mloginurl()" class="btn btn-warning btn-block">跳转QQ快捷登录</button><br/><button type="button" onclick="loadScript()" class="btn btn-success btn-block">我已完成登录</button></div>
		<div class="list-group-item">
		<div class="btn-group">
		<a href="Login.php" class="btn btn-primary btn-rounded"><i class="fa fa-user"></i>&nbsp;返回登录</a>
		<a href="Login.php" class="btn btn-info btn-rounded"><i class="fa fa-user-plus"></i>&nbsp;注册账号</a>
		</div>
		</div>
	</div>
</div>
</form>
</div>
<div class="text-center">
<p>
<small class="text-muted"><a href="/"><?php echo $conf['sitename']?></a><br>&copy; 2016~2021</small>
</p>
</div>
</div>
</div>
<script src="//cdn.staticfile.org/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="/Core/Assets/Layer/layer.js"></script>
<script src="./assets/js/qrlogin.js"></script>
</body>
</html>
<?php
	}
?>