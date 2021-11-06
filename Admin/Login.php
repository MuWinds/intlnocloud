<?php
// +----------------------------------------------------------------------
// | Quotes [ 只为给用户更好的体验]**[我知道发出来有人会盗用,但请您留版权]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小白杨  <2645147933@qq.com>        Bycode          盗用不留版权,你就不配拿去!
// +----------------------------------------------------------------------
// | Date: 2021年03月24日
// +----------------------------------------------------------------------

include("../Core/Common.php");
if(isset($_GET['logout'])){
	setcookie("admin_token", "", time() - 604800);
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已成功注销本次登陆！');window.location.href='./Login.php';</script>");
}elseif($islogin_admin==1){
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理登录 | <?=$conf['sitename']?>-<?=$conf['title']?></title>
<link rel="stylesheet" type="text/css" href="../Core/Assets/Css/login1.css?v=1.2">
    <!-- 作者QQ:951252660 -->
</head>

<body>

    <div class="login">
        <div class="center">
            <h1><img src="../Core/Assets/Img/logo.png" alt=""></h1>
            <form action="#">
                <div class="inputLi">
                    <input type="text" class="user" placeholder="请输入" required id="admin_user"> 
                </div>
                <div class="inputLi">
                    <input type="password" class="pwd" placeholder="请输入" required id="admin_pass">
                </div>
                <div class="inputLi">
                    <button id="login">登录</button>
                </div>
            </form>
        </div>
    </div>
	
</body>

<script src="../Core/Assets/Js/jquery.min.js"></script>
<script src="../Core/Assets/Layer/layer.js"></script>
<script type="text/javascript">
	function check_login()
	{
		var admin_user=$("#admin_user").val();
		var admin_pass=$("#admin_pass").val();
		var ii = layer.load(5, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "Ajax.php?act=Login",
			data : {"admin_user":admin_user,"admin_pass":admin_pass},
			dataType : 'json',
			timeout: 15000, //ajax请求超时时间15s
			success : function(data) {					  
				layer.close(ii);
				layer.msg(data.msg);
				if(data.code==1){
					setTimeout(function () {
					location.href="./";
					}, 1000); //延时1秒跳转
				}else{
					$("#login_form").removeClass('shake_effect');  
					setTimeout(function()
					{
						$("#login_form").addClass('shake_effect')
					},1); 
				}
			},
			error:function(data){
				layer.close(ii);
				layer.msg('服务器错误');
				}
		});
	}
	$(function(){
		$("#create").click(function(){
			check_register();
			return false;
		})
		$("#login").click(function(){
			check_login();
			return false;
		})
		$('.message a').click(function () {
		    $('form').animate({
		        height: 'toggle',
		        opacity: 'toggle'
		    }, 'slow');
		});
	})
	</script>
</html>