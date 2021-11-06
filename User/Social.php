<?php 
require '../Core/Common.php';
$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']. '/';//获取本地域名
$allapi	 = $conf['Instant_url'];//intl官方QQ快捷登录API地址
require_once("../Core/Core_Class/Oauth.class.php");
$Oauth = new Oauth();
if ($_GET['code']) {
    $array = $Oauth->callback();
    $social_uid	  	=	 $array['social_uid'];//固定值 可作为账号
    $access_token 	=	 $array['access_token'];//固定值 可作为密码
    $gender		  	=	 $array['gender'];//性别
	$nickname	 	=	 $array['nickname'];//QQ名称
    $figureurl_qq_1 =	 $array['figureurl_qq_1'];//大小为40×40像素的QQ头像URL
    $figureurl_qq_2	=	 $array['figureurl_qq_2'];//[大小为100×100像素的QQ头像URL。不是所有的用户都拥有QQ的100×100的头像。]
	$vip	 	 	=	 $array['vip'];//标识用户是否为黄钻用户（0：不是；1：是）
    $level			=	 $array['level'];//黄钻等级
	$is_yellow_year_vip= $array['is_yellow_year_vip'];//标识是否为年费黄钻用户（0：不是； 1：是）
	
	$_SESSION['social_uid'] 	=  $social_uid;//QQ登录返回的uid 固定值
	$_SESSION['access_token'] 	=  $access_token;//QQ登录返回的token  固定值
	$_SESSION['nickname']		=  $nickname;//QQ网名
	$user_row=$DB->query("SELECT * FROM pay_user WHERE social_uid='{$social_uid}' limit 1")->fetch();
	$pid=$user_row['pid'];
	$key=$user_row['key'];
	if($user_row){
		if($islogin_user==1){
			@header('Content-Type: text/html; charset=UTF-8');
			exit("<script language='javascript'>alert('当前QQ已绑定商户ID:{$user_row['pid']}，请勿重复绑定！');window.location.href='./Api_Set.php';</script>");
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
		$city=get_ip_city($ip)['Result']['Country'];
		unset($_SESSION['social_uid']);
		unset($_SESSION['access_token']);
		unset($_SESSION['nickname']);
		$DB->exec("insert into `pay_log` (`pid`,`type`,`date`,`ip`,`city`) values ('".$userrow['pid']."','INTL官方QQ快捷登录','".$date."','".$ip."','".$city."')");
		exit("<script language='javascript'>alert('[{$_SESSION['nickname']}]登录成功,欢迎回来！');window.location.href='./';</script>");
	}elseIf($islogin_user==1){
		$DB->exec("update `pay_user` set `social_uid` ='{$_SESSION['social_uid']}',`nickname` ='{$_SESSION['nickname']}' where `pid`='{$userrow['pid']}'");
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('商户:{$userrow['pid']}已成功绑定QQ账号:{$_SESSION['nickname']}.！');window.location.href='./Api_Set.php';</script>");
	}else{
		exit("<script language='javascript'>alert('请输入商户ID和密钥完成登录');window.location.href='./Login.php?connect=qqlogin';</script>");
	}
		
	
}elseif($islogin_user==1 && isset($_GET['unbind'])) {
	$DB->exec("update `pay_user` set `social_uid` =NULL,`nickname` =NULL where `pid`='{$userrow['pid']}'");
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已成功解绑QQ账号！');window.location.href='./Api_Set.php';</script>");	
	
}elseif($islogin_user!=1 && isset($_GET['unbind'])){
	exit("<script language='javascript'>alert('您未登录,无法解绑！');window.location.href='./';</script>");	
}elseif(!$_GET['connect']){
    $Oauth->login();
	
}else{
	?>
	INTL登陆绑定页面
<?php
	}
?>