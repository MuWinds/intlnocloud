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
if(isset($_GET['regok'])){
	exit("<script language='javascript'>alert('恭喜你，商户注册成功！');window.location.href='./Login.php';</script>");
}
if(isset($_GET['logout'])){
	setcookie("user_token", "", time() - 604800);
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已成功注销本次登陆！');window.location.href='./Login.php';</script>");
}elseif($islogin_user==1){
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}
$csrf_token = md5(mt_rand(0,999).time());
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
<title>商户登录-<?=$conf['sitename']?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--图标库-->
<!--link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css'-->
<!--响应式框架-->
<link rel='stylesheet' href='/Core/Assets/Login/css/bootstrap.min.css'>
<!--主要样式-->
<link rel="stylesheet" href="/Core/Assets/Login/css/style.css">
</head>
<body>
<div class="container">
	<div class="card-wrap">
		<div class="card border-0 shadow card--welcome is-show" id="welcome">
			<div class="card-body">
				<h2 class="card-title">欢迎光临</h2>
				<p>欢迎进入登录页面</p>
				<div class="btn-wrap">
					<a class="btn btn-lg btn-register js-btn" data-target="register">注册</a>
					<a class="btn btn-lg btn-login js-btn" data-target="login">登录</a>
				</div>
			</div>
		</div>
		<div class="card border-0 shadow card--register" id="register">
			<div class="card-body">
				<h2 class="card-title">会员注册</h2>
				<?php if($conf['reg_pay']){?>
                    <p>商户申请价格为：<b><?php echo $conf['reg_pay_price']?></b> 元</p>
				<?php }?>
					<form name="form" class="form-validation">
						<input type="hidden" name="csrf_token" value="<?php echo $csrf_token?>"><input type="hidden" name="verifytype" value="<?php echo $conf['verifytype']?>">
					<div class="form-group">
						<input class="form-control" type="text" placeholder="登陆账号" name="user" required>
					</div>
					<div class="form-group">
						<input class="form-control" type="text" placeholder="登录密码" name="pass" required>
					</div>
					<div class="form-group">
						<input class="form-control" type="text" placeholder="联系QQ" name="qq" required>
					</div>
					<?php if($conf['verifytype']==1){?>
						<div class="form-group">
                           <input class="form-control common-input" type="text" name="phone" placeholder="手机号码" required>
                        </div>
						<div class="input-group">
						    <input class="form-control " type="text" name="code" placeholder="短信验证码" required>
						<div class="form-group">
						    <input class="form-control " type="button" id="sendcode" value="点击获取" required></button>
						</div>
					<?php }else{?>
						<div class="form-group">
						<input class="form-control" type="email" placeholder="邮箱"  name="email" required="required"/>
					</div>
						<div class="input-group">
						<input type="text" name="code" placeholder="邮箱验证码" class="form-control" required>
						<div class="form-group">
						    <input class="form-control " type="button" id="sendcode" value="点击获取" required></button>
						</div>
				<?php }?>
						</div>
					<button class="btn btn-lg" type="button" id="submit" ng-disabled='form.$invalid'>注册</button>
				</form>
			</div>
			<button class="btn btn-back js-btn" data-target="welcome"><i class="fas fa-angle-left"></i></button>
		</div>
		<div class="card border-0 shadow card--login" id="login">
			<div class="card-body">
				<h2 class="card-title">欢迎登录</h2>
					<input type="hidden" name="connect" id="connect" value="<?php echo $_GET['connect']?>">
					<div class="form-group">
						<input class="form-control" type="pid" placeholder="账号/商户ID" id="pid" name="pid" required="required"/>
					</div>
					<div class="form-group">
						<input class="form-control" type="password" placeholder="密码/商户KEY" id="key"   name="key" required="required"/>
					</div>
					<p><a href="">忘记密码请用绑定QQ扫码登陆</a></p>
					<?php if($_GET['connect']){ ?>
						<button class="btn btn-lg" type="submit" onclick="check_login();">登录并绑定</button>
					<?php }else{ ?>
						<button class="btn btn-lg" type="submit" onclick="check_login();">登录</button>
				
<div class="wrapper text-center">
<?php if($conf['login_alipay']>0){?>
<button type="button" class="btn btn-rounded btn-lg btn-icon btn-default" title="支付宝快捷登录" onclick="window.location.href='Oauth.php'"><img src="/Core/Assets/Icon/alipay.ico" style="border-radius:50px;"></button>
<?php }?>
<?php if($conf['login_qq']==4){?>
</br>
<button type="button" class="btn btn-lg btn-primary btn-block" title="绑定QQ扫码登录" onclick="window.location.href='Connect.php'"><i class="fab fa-qq"></i>QQ扫码</button>
</br>
<button type="button" class="btn btn-lg btn-primary btn-block" title="绑定QQ快捷登录" onclick="window.location.href='Social.php'"><i class="fab fa-qq"></i>QQ快捷</button>
</br>
<?php }?>
<?php if($conf['login_qq']==2){?>
<button type="button" class="btn btn-rounded btn-lg btn-icon btn-default" title="绑定QQ扫码登录" onclick="window.location.href='Social.php'"><i class="fab fa-qq"></i>QQ快捷登录</button>
<?php }?>
<?php if($conf['login_qq']==3){?>
<button type="button" class="btn btn-rounded btn-lg btn-icon btn-default" title="绑定QQ扫码登录" onclick="window.location.href='Connect.php'"><i class="fab fa-qq"></i>绑定QQ扫码登录</button>
<?php }?>
<?php if($conf['login_wx']>0){?>
<button type="button" class="btn btn-rounded btn-lg btn-icon btn-default" title="微信快捷登录" onclick="window.location.href='Wxlogin.php'"><i class="fa fa-weixin" style="color: green"></i></button>
<?php }?>
					<?php } ?>
				</form>
			</div>
			<button class="btn btn-back js-btn" data-target="welcome"><i class="fas fa-angle-left"></i></button>
		</div>
		
	</div>
	
</div>
</div>
  
<script src="/Core/Assets/Login/js/index.js"></script>
<script src="//cdn.staticfile.org/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//lib.baomitu.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="/Core/Assets/Layer/layer.js"></script>
<script src="//static.geetest.com/static/tools/gt.js"></script>

  
<!--script src="/Core/Assets/Login/js/index.js"></script>
<script src="/Core/Assets/Login/js/jquery.min.js"></script>
<script src="//static.geetest.com/static/tools/gt.js"></script>
<script src="/Core/Assets/layer.js"></script>
<script src="/Core/Assets/Login/js/jquery.cookie.min.js"></script-->

<script>
	function check_login()
	{
		var pid=$("#pid").val();
		var key=$("#key").val();
		var connect=$("#connect").val();
		var ii = layer.load(5, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "Ajax.php?act=Login",
			data : {pid,key,connect},
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
</script>
<script>
/*
function invokeSettime(obj){
    var countdown=60;
    settime(obj);
    function settime(obj) {
        if (countdown == 0) {
            $(obj).attr("data-lock", "false");
            $(obj).text("获取验证码");
            countdown = 60;
            return;
        } else {
			$(obj).attr("data-lock", "true");
            $(obj).attr("disabled",true);
            $(obj).text("(" + countdown + ") s 重新发送");
            countdown--;
        }
        setTimeout(function() {
                    settime(obj) }
                ,1000)
    }
}
*/
var handlerEmbed = function (captchaObj) {
	var sendto;
	captchaObj.onReady(function () {
		$("#wait").hide();
	}).onSuccess(function () {
		var result = captchaObj.getValidate();
		if (!result) {
			return alert('请完成验证');
		}
		var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "Ajax.php?act=Sendcode",
			data : {sendto:sendto,geetest_challenge:result.geetest_challenge,geetest_validate:result.geetest_validate,geetest_seccode:result.geetest_seccode},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					new invokeSettime("#sendcode");
					layer.msg('发送成功，请注意查收！');
				}else{
					layer.alert(data.msg);
					captchaObj.reset();
				}
			} 
		});
	});
	$('#sendcode').click(function () {
		if ($(this).attr("data-lock") === "true") return;
		if($("input[name='verifytype']").val()=='1'){
			sendto=$("input[name='phone']").val();
			if(sendto==''){layer.alert('手机号码不能为空！');return false;}
			if(sendto.length!=11){layer.alert('手机号码不正确！');return false;}
		}else{
			sendto=$("input[name='email']").val();
			if(sendto==''){layer.alert('邮箱不能为空！');return false;}
			var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
			if(!reg.test(sendto)){layer.alert('邮箱格式不正确！');return false;}
		}
		captchaObj.verify();
	});
};
$(document).ready(function(){
	$("#submit").click(function(){
		if ($(this).attr("data-lock") === "true") return;
		var email=$("input[name='email']").val();
		var phone=$("input[name='phone']").val();
		var code=$("input[name='code']").val();
		var user=$("input[name='user']").val();
		var pass=$("input[name='pass']").val();
		var qq=$("input[name='qq']").val();
		if(email=='' || phone=='' || code=='' || user=='' || pass=='' || qq==''){layer.alert('请确保各项不能为空！');return false;}
		//if(pwd!=pwd2){layer.alert('两次输入密码不一致！');return false;}
		if($("input[name='verifytype']").val()=='1'){
			if(phone.length!=11){layer.alert('手机号码不正确！');return false;}
		}else{
			var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
			if(!reg.test(email)){layer.alert('邮箱格式不正确！');return false;}
		}
		var ii = layer.load();
		$(this).attr("data-lock", "true");
		var csrf_token=$("input[name='csrf_token']").val();
		$.ajax({
			type : "POST",
			url : "Ajax.php?act=Reg",
			data : {email:email,phone:phone,code:code,user:user,pass:pass,qq:qq,csrf_token:csrf_token},
			dataType : 'json',
			success : function(data) {
				$("#submit").attr("data-lock", "false");
				layer.close(ii);
				if(data.code == 1){
					layer.alert('恭喜你，商户申请成功！', {icon: 1}, function(){
						window.location.href="./Login.php";
					});
				}else if(data.code == 2){
					var paymsg = '';
					$.each(data.paytype, function(key, value) {
							paymsg+='<button class="btn btn-default btn-block" onclick="window.location.href=\'SDK/epayapi.php?type='+value.name+'&WIDout_trade_no='+data.trade_no+'&WIDsubject=%E7%94%B3%E8%AF%B7%E5%95%86%E6%88%B7\'" style="margin-top:10px;"><img width="20" src="/Core/Assets/Icon/'+value.name+'.ico" class="logo">'+value.showname+'</button>';
					});
					layer.alert('<center><h2>￥ '+data.need+'</h2><hr>'+paymsg+'<hr>提示：支付完成后即可直接登录</center>',{
						btn:[],
						title:'支付确认页面',
						closeBtn: false
					});
				}else{
					layer.alert(data.msg);
				}
			}
		});
	});
	$.ajax({
		// 获取id，challenge，success（是否启用failback）
		url: "Ajax.php?act=Captcha&t=" + (new Date()).getTime(), // 加随机数防止缓存
		type: "get",
		dataType: "json",
		success: function (data) {
			console.log(data);
			// 使用initGeetest接口
			// 参数1：配置参数
			// 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
			initGeetest({
				width: '100%',
				gt: data.gt,
				challenge: data.challenge,
				new_captcha: data.new_captcha,
				product: "bind", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
				offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
				// 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
			}, handlerEmbed);
		}
	});
	/*
	<?php if(!empty($conf['zhuce'])){?>
	$('#myModal').modal('show');
	<?php }?>
	*/
});
</script>
</body>
</html>