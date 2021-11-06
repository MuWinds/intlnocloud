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

/**
 * 系统设置
**/
$title='系统设置';
include './Head.php';
?>
<?php
$mod=isset($_GET['mod'])?$_GET['mod']:null;
$mods=['site'=>'网站信息','oauth'=>'快捷登录','template'=>'首页模板','pay'=>'支付设置','Notice'=>'公告与排版','mail'=>'邮箱与短信','upimg'=>'LOGO设置','iptype'=>'IP地址','cron'=>'加快回调监控','account'=>'修改密码'];
?>
<ul class="nav nav-pills">
	<?php foreach($mods as $key=>$name){echo '<li class="'.($key==$mod?'active':null).'"><a href="Set.php?mod='.$key.'">'.$name.'</a></li>';} ?>
</ul>
<?php
if($mod=='site'){
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">网站信息配置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
	<div class="form-group">
	  <label class="col-sm-2 control-label">网站名称</label>
	  <div class="col-sm-10"><input type="text" name="sitename" value="<?php echo $conf['sitename']; ?>" class="form-control" required/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">首页标题</label>
	  <div class="col-sm-10"><input type="text" name="title" value="<?php echo $conf['title']; ?>" class="form-control" required/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">关键字</label>
	  <div class="col-sm-10"><input type="text" name="keywords" value="<?php echo $conf['keywords']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">网站描述</label>
	  <div class="col-sm-10"><input type="text" name="description" value="<?php echo $conf['description']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">客服ＱＱ</label>
	  <div class="col-sm-10"><input type="text" name="qq" value="<?php echo $conf['qq']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">加群链接</label>
	  <div class="col-sm-10"><input type="text" name="qq_qun" value="<?php echo $conf['qq_qun']; ?>" class="form-control"/></div>
	</div><br/>	
	<div class="form-group">
	  <label class="col-sm-2 control-label">开放注册</label>
	  <div class="col-sm-10"><select class="form-control" name="reg_open" default="<?php echo $conf['reg_open']?>"><option value="1">开启</option><option value="0">关闭</option></select></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">用户验证方式</label>
	  <div class="col-sm-10"><select class="form-control" name="verifytype" default="<?php echo $conf['verifytype']?>"><option value="0">邮箱验证</option><option value="1">手机验证</option></select></div>
	</div><br/>
	<div id="setform1" style="<?php echo $conf['reg_open']==0?'display:none;':null; ?>">
	<div class="form-group">
	  <label class="col-sm-2 control-label">注册赠送额度</label>
	  <div class="col-sm-10"><input type="text" name="reg_money" value="<?php echo $conf['reg_money']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">注册付费</label>
	  <div class="col-sm-10"><select class="form-control" name="reg_pay" default="<?php echo $conf['reg_pay']?>"><option value="1">开启</option><option value="0">关闭</option></select></div>
	</div><br/>
	<div id="setform2" style="<?php echo $conf['reg_pay']==0?'display:none;':null; ?>">
	<div class="form-group">
	  <label class="col-sm-2 control-label">注册付费金额</label>
	  <div class="col-sm-10"><input type="text" name="reg_pay_price" value="<?php echo $conf['reg_pay_price']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">注册付费收款商户ID</label>
	  <div class="col-sm-10"><input type="text" name="zero_pid" value="<?php echo $conf['zero_pid']; ?>" class="form-control" placeholder="填写在本站注册的商户PID"/></div><font color="green">这个商户ID跟开通免挂会员收款商户ID跟注册收款商户ID、在线测试页面商户ID同步</font>
	</div><br/>
	</div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">测试支付</label>
	  <div class="col-sm-10"><select class="form-control" name="test_open" default="<?php echo $conf['test_open']?>"><option value="1">开启</option><option value="0">关闭</option></select></div>
	</div><br/>
	<div id="setform3" style="<?php echo $conf['test_open']==0?'display:none;':null; ?>">
	<div class="form-group">
	  <label class="col-sm-2 control-label">测试支付收款商户ID</label>
	  <div class="col-sm-10"><input type="text" name="zero_pid" value="<?php echo $conf['zero_pid']; ?>" class="form-control" placeholder="也是用于充值额度收款ID填写在本站注册的商户PID"/></div><font color="green">这个商户ID跟开通免挂会员收款商户ID跟注册收款商户ID、在线测试页面商户ID同步</font>
	</div><br/>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">极限验证码ID</label>
	  <div class="col-sm-10"><input type="text" name="captcha_id" value="<?php echo $conf['captcha_id']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">极限验证码密钥</label>
	  <div class="col-sm-10"><input type="text" name="captcha_key" value="<?php echo $conf['captcha_key']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">登录开启验证码</label>
	  <div class="col-sm-10"><select class="form-control" name="captcha_open_login" default="<?php echo $conf['captcha_open_login']?>"><option value="0">关闭</option><option value="1">开启</option></select></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
  </form>
</div>
</div>
<script>
$("select[name='reg_open']").change(function(){
	if($(this).val() == 1){
		$("#setform1").show();
	}else{
		$("#setform1").hide();
	}
});
$("select[name='reg_pay']").change(function(){
	if($(this).val() == 1){
		$("#setform2").show();
	}else{
		$("#setform2").hide();
	}
});
$("select[name='test_open']").change(function(){
	if($(this).val() == 1){
		$("#setform3").show();
	}else{
		$("#setform3").hide();
	}
});
</script>
<?php
}elseif($mod=='account_n' && $_POST['do']=='submit'){
	$user=$_POST['user'];
	$oldpwd=$_POST['oldpwd'];
	$newpwd=$_POST['newpwd'];
	$newpwd2=$_POST['newpwd2'];
	if($user==null)showmsg('用户名不能为空！',3);
	saveSetting('admin_user',$user);
	if(!empty($newpwd) && !empty($newpwd2)){
		if($oldpwd!=$conf['admin_pass'])showmsg('旧密码不正确！',3);
		if($newpwd!=$newpwd2)showmsg('两次输入的密码不一致！',3);
		saveSetting('admin_pass',$newpwd);
	}
	$ad=$CACHE->clear();
	if($ad)showmsg('修改成功！请重新登录',1);
	else showmsg('修改失败！<br/>'.$DB->error(),4);
}elseif($mod=='account'){
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">管理员账号配置</h3></div>
<div class="panel-body">
  <form action="./Set.php?mod=account_n" method="post" class="form-horizontal" role="form"><input type="hidden" name="do" value="submit"/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">用户名</label>
	  <div class="col-sm-10"><input type="text" name="user" value="<?php echo $conf['admin_user']; ?>" class="form-control" required/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">旧密码</label>
	  <div class="col-sm-10"><input type="password" name="oldpwd" value="" class="form-control" placeholder="请输入当前的管理员密码"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">新密码</label>
	  <div class="col-sm-10"><input type="password" name="newpwd" value="" class="form-control" placeholder="不修改请留空"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">重输密码</label>
	  <div class="col-sm-10"><input type="password" name="newpwd2" value="" class="form-control" placeholder="不修改请留空"/></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
  </form>
</div>
</div>
<?php
}elseif($mod=='template'){
	$mblist = \lib\Template::getList();
?>
<style>.mblist{margin-bottom: 20px;} .mblist img{height: 110px;}</style>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">首页模板设置</h3></div>
<div class="panel-body">
  <h4>当前使用模板：</h4>
  <div class="row text-center">
	  <div class="col-xs-6 col-sm-4">
		<img class="img-responsive img-thumbnail img-rounded" src="/Template/<?php echo $conf['template']?>/preview.png" onerror="this.src='/assets/img/NoImg.png'">
	  </div>
	  <div class="col-xs-6 col-sm-4">
		<p>模板名称：<?php echo $conf['template']?></p>
	  </div>
  </div>
  <hr/>
  <h4>[可以向作者提交自己写的首页模板(有奖励)]->更换模板：</h4>
  <div class="row text-center">
  <?php foreach($mblist as $template){?>
	  <div class="col-xs-6 col-sm-4 mblist">
		<a href="javascript:changeTemplate('<?php echo $template?>')"><img class="img-responsive img-thumbnail img-rounded" src="/Template/<?php echo $template?>/preview.png" onerror="this.src='/assets/img/NoImg.png'" title="点击更换到该模板"><br/><?php echo $template?></a>
	  </div>
  <?php }?>
  </div>
</div>
</div>
<?php
}elseif($mod=='iptype'){
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">用户IP地址获取设置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
    <div class="form-group">
	  <label class="col-sm-2 control-label">用户IP地址获取方式</label>
	  <div class="col-sm-10"><select class="form-control" name="ip_type" default="<?php echo $conf['ip_type']?>"><option value="0">0_X_FORWARDED_FOR</option><option value="1">1_X_REAL_IP</option><option value="2">2_REMOTE_ADDR</option></select></div>
	</div>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
  </form>
</div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
此功能设置用于防止用户伪造IP请求。<br/>
X_FORWARDED_FOR：之前的获取真实IP方式，极易被伪造IP<br/>
X_REAL_IP：在网站使用CDN的情况下选择此项，在不使用CDN的情况下也会被伪造<br/>
REMOTE_ADDR：直接获取真实请求IP，无法被伪造，但可能获取到的是CDN节点IP<br/>
<b>你可以从中选择一个能显示你真实地址的IP，优先选下方的选项。</b>
</div>
</div>
<script>
$(document).ready(function(){
	$.ajax({
		type : "GET",
		url : "Ajax.php?act=iptype",
		dataType : 'json',
		async: true,
		success : function(data) {
			$("select[name='ip_type']").empty();
			var defaultv = $("select[name='ip_type']").attr('default');
			$.each(data, function(k, item){
				$("select[name='ip_type']").append('<option value="'+k+'" '+(defaultv==k?'selected':'')+'>'+ item.name +' - '+ item.ip +' '+ item.city +'</option>');
			})
		}
	});
})
</script>
<?php
}elseif($mod=='pay_vip'){
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">免挂会员价格配置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
  <h4 style="text-align: center;">免挂会员价格配置</h4>
	<div class="form-group">
	  <label class="col-sm-2 control-label">支付宝免挂一个月价格</label>
	  <div class="col-sm-9"><input type="text" name="alipay_free_vip_money" value="<?php echo $conf['alipay_free_vip_money']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">QQ钱包免挂一个月价格</label>
	  <div class="col-sm-9"><input type="text" name="qqpay_free_vip_money" value="<?php echo $conf['qqpay_free_vip_money']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">微信免挂一个月价格</label>
	  <div class="col-sm-9"><input type="text" name="wxpay_free_vip_money" value="<?php echo $conf['wxpay_free_vip_money']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">允许商户上架二维码数量</label>
	  <div class="col-sm-9"><input type="text" name="qr_nums" value="<?php echo $conf['qr_nums']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">额度充值比例1元=?额度</label>
	  <div class="col-sm-9"><input type="text" name="ed_money" value="<?php echo $conf['ed_money']; ?>" class="form-control"/>
	  <pre><font color="green">1元=30  10=300  那么费率就差不多是百分之3 别人跑1000量,你赚30</font></pre></div>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">会员开通收款商户ID</label>
	  <div class="col-sm-9"><input type="text" name="zero_pid" value="<?php echo $conf['zero_pid']; ?>" class="form-control" placeholder="填写在本站注册的商户PID"/><pre><font color="green">这个会员开通收款商户ID跟注册收款商户ID、在线测试页面商户ID同步</font></pre></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-3 col-sm-9"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
  </form>
</div>
</div>
<?php
}elseif($mod=='pay'){
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">支付配置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
  <h4 style="text-align: center;">支付配置</h4>
	<div class="form-group">
	  <label class="col-sm-3 control-label">最大支付金额</label>
	  <div class="col-sm-9"><input type="text" name="pay_maxmoney" value="<?php echo $conf['pay_maxmoney']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-3 control-label">最小支付金额</label>
	  <div class="col-sm-9"><input type="text" name="pay_minmoney" value="<?php echo $conf['pay_minmoney']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-3 control-label">商品屏蔽关键词</label>
	  <div class="col-sm-9"><input type="text" name="blockname" value="<?php echo $conf['blockname']; ?>" class="form-control"/><font color="green">多个关键词用|隔开。如果触发屏蔽会在<a href="./Risk.php">风控记录</a>里面显示</font></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-3 control-label">商品屏蔽显示内容</label>
	  <div class="col-sm-9"><input type="text" name="blockalert" value="<?php echo $conf['blockalert']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-3 control-label">商品名称自定义</label>
	  <div class="col-sm-9"><input type="text" name="ordername" value="<?php echo $conf['ordername']; ?>" class="form-control" placeholder="默认使用原商品名称"/><font color="green">支持变量值：[name]原商品名称，[time]时间戳，[qq]当前商户的联系QQ</font></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-3 col-sm-9"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
  </form>
</div>
</div>
<?php
}elseif($mod=='Notice'){
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">其他公告与排版设置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
	<div class="form-group">
	  <label class="col-sm-2 control-label">用户中心弹出公告</label>
	  <div class="col-sm-10"><textarea class="form-control" name="modal" rows="5" placeholder="不填写则不显示弹出公告"><?php echo $conf['modal']?></textarea></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">首页底部排版</label>
	  <div class="col-sm-10"><textarea class="form-control" name="footer" rows="3" placeholder="可填写备案号等"><?php echo $conf['footer']?></textarea></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/><br/>
	  <a href="./Notice.php" class="btn btn-default btn-block">用户中心公告列表</a>
	 </div>
	</div>
  </form>
</div>
<?php
}elseif($mod=='oauth'){
	//$alipay_channel = $DB->getAll("SELECT * FROM pre_channel WHERE plugin='alipay'");
	//$wxpay_channel = $DB->getAll("SELECT * FROM pre_channel WHERE plugin='wxpay'");
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">快捷登录配置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
	<div class="form-group">
	  <label class="col-sm-3 control-label">QQ快捷登录</label>
	  <div class="col-sm-9"><select class="form-control" name="login_qq" default="<?php echo $conf['login_qq']?>"><option value="0">关闭</option><option value="1">腾讯官方QQ互联快捷登录(需申请)</option><option value="2">INTL官方QQ互联(免申请)</option><option value="3">QQ扫码快捷登录(免申请有异地风险)[当前模式暂未集成]</option><option value="4">INTL官方QQ互联与QQ扫码快捷登陆共存(免申请)</option></select></div>
	</div><br/>
	<div id="setform1" style="<?php echo $conf['login_qq']!=1?'display:none;':null; ?>">
	<div class="form-group">
	  <label class="col-sm-3 control-label">QQ快捷登录Appid</label>
	  <div class="col-sm-9"><input type="text" name="login_qq_appid" value="<?php echo $conf['login_qq_appid']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-3 control-label">QQ快捷登录Appkey</label>
	  <div class="col-sm-9"><input type="text" name="login_qq_appkey" value="<?php echo $conf['login_qq_appkey']; ?>" class="form-control"/></div>
	</div>
	<a href="https://connect.qq.com" target="_blank" rel="noreferrer">申请地址</a>，回调地址填写：<?php echo $siteurl.'User/Connect.php';?><br/>
	</div>
	<!--div class="form-group">
	  <label class="col-sm-3 control-label">支付宝快捷登录</label>
	  <div class="col-sm-9"><select class="form-control" name="login_alipay" default="<?php echo $conf['login_alipay']?>"><option value="0">关闭</option><?php foreach($alipay_channel as $channel){echo '<option value="'.$channel['id'].'">'.$channel['name'].'</option>';} ?></select><font color="green">请先添加支付插件为alipay的支付通道</font><br/><a href="https://openhome.alipay.com/platform/appManage.htm" target="_blank" rel="noreferrer">申请地址</a>，应用内添加功能"获取会员信息"，授权回调地址填写：<?php echo $siteurl.'user/oauth.php';?></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-3 control-label">微信快捷登录</label>
	  <div class="col-sm-9"><select class="form-control" name="login_wx" default="<?php echo $conf['login_wx']?>"><option value="0">关闭</option><?php foreach($wxpay_channel as $channel){echo '<option value="'.$channel['id'].'">'.$channel['name'].'</option>';} ?></select><font color="green">请先添加支付插件为wxpay的支付通道</font><br/>需要有服务号，在公众号后台配置网页授权域名：<?php echo $_SERVER['HTTP_HOST'];?></div>
	</div><br/-->
	<div class="form-group">
	  <div class="col-sm-offset-3 col-sm-9"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
  </form>
</div>
</div>
<script>
$("select[name='login_qq']").change(function(){
	if($(this).val() == 1){
		$("#setform1").show();
	}else{
		$("#setform1").hide();
	}
});
</script>
<?php
}elseif($mod=='mailtest'){
	$mail_name = $conf['mail_recv']?$conf['mail_recv']:$conf['mail_name'];
	if(!empty($mail_name)){
	$result=send_mail($mail_name,'邮件发送测试。','这是一封测试邮件！<br/><br/>来自：'.$siteurl);
	if($result==1)
		showmsg('邮件发送成功！',1);
	else
		showmsg('邮件发送失败！'.$result,3);
	}
	else
		showmsg('您还未设置邮箱！',3);
}elseif($mod=='mail'){
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">发信邮箱设置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
	<div class="form-group">
	  <label class="col-sm-2 control-label">发信模式</label>
	  <div class="col-sm-10"><select class="form-control" name="mail_cloud" default="<?php echo $conf['mail_cloud']?>"><option value="0">SMTP发信</option><option value="1">搜狐Sendcloud</option><option value="2">阿里云邮件推送</option></select></div>
	</div><br/>
	<div id="frame_set1" style="<?php echo $conf['mail_cloud']>1?'display:none;':null; ?>">
	<div class="form-group">
	  <label class="col-sm-2 control-label">SMTP服务器</label>
	  <div class="col-sm-10"><input type="text" name="mail_smtp" value="<?php echo $conf['mail_smtp']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">SMTP端口</label>
	  <div class="col-sm-10"><input type="text" name="mail_port" value="<?php echo $conf['mail_port']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">邮箱账号</label>
	  <div class="col-sm-10"><input type="text" name="mail_name" value="<?php echo $conf['mail_name']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">邮箱密码</label>
	  <div class="col-sm-10"><input type="text" name="mail_pwd" value="<?php echo $conf['mail_pwd']; ?>" class="form-control"/></div>
	</div><br/>
	</div>
	<div id="frame_set2" style="<?php echo $conf['mail_cloud']==0?'display:none;':null; ?>">
	<div class="form-group">
	  <label class="col-sm-2 control-label">API_USER</label>
	  <div class="col-sm-10"><input type="text" name="mail_apiuser" value="<?php echo $conf['mail_apiuser']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">API_KEY</label>
	  <div class="col-sm-10"><input type="text" name="mail_apikey" value="<?php echo $conf['mail_apikey']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">发信邮箱</label>
	  <div class="col-sm-10"><input type="text" name="mail_name2" value="<?php echo $conf['mail_name2']; ?>" class="form-control"/></div>
	</div><br/>
	</div>
	<div class="form-group">
	  <label class="col-sm-2 control-label">收信邮箱</label>
	  <div class="col-sm-10"><input type="text" name="mail_recv" value="<?php echo $conf['mail_recv']; ?>" class="form-control" placeholder="不填默认为发信邮箱"/></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/><?php if($conf['mail_name']){?>[<a href="Set.php?mod=mailtest">给 <?php echo $conf['mail_recv']?$conf['mail_recv']:$conf['mail_name']?> 发一封测试邮件</a>]<?php }?>
	 </div><br/>
	</div>
  </form>
</div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
使用普通模式发信时，建议使用QQ邮箱，SMTP服务器smtp.qq.com，端口465，密码不是QQ密码也不是邮箱独立密码，是QQ邮箱设置界面生成的<a href="https://service.mail.qq.com/cgi-bin/help?subtype=1&&no=1001256&&id=28"  target="_blank" rel="noreferrer">授权码</a>。<br/>阿里云邮件推送：<a href="https://www.aliyun.com/product/directmail" target="_blank" rel="noreferrer">点此进入</a>｜<a href="https://usercenter.console.aliyun.com/#/manage/ak" target="_blank" rel="noreferrer">获取AK/SK</a>
</div>
</div>
<script>
$("select[name='mail_cloud']").change(function(){
	if($(this).val() == 0){
		$("#frame_set1").show();
		$("#frame_set2").hide();
	}else{
		$("#frame_set1").hide();
		$("#frame_set2").show();
	}
});
</script>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">短信接口设置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
	<div class="form-group">
	  <label class="col-sm-2 control-label">接口选择</label>
	  <div class="col-sm-10"><select class="form-control" name="sms_api" default="<?php echo $conf['sms_api']?>"><option value="0">978W短信接口</option><option value="1">腾讯云短信接口</option><option value="2">阿里云短信接口</option></select></div>
	</div><br/>
	<div class="form-group" id="showAppId" style="<?php echo $conf['sms_api']==0?'display:none;':null; ?>">
	  <label class="col-sm-2 control-label">AppId</label>
	  <div class="col-sm-10"><input type="text" name="sms_appid" value="<?php echo $conf['sms_appid']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">AppKey</label>
	  <div class="col-sm-10"><input type="text" name="sms_appkey" value="<?php echo $conf['sms_appkey']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group" id="showSign" style="<?php echo $conf['sms_api']==0?'display:none;':null; ?>">
	  <label class="col-sm-2 control-label">短信签名内容</label>
	  <div class="col-sm-10"><input type="text" name="sms_sign" value="<?php echo $conf['sms_sign']; ?>" class="form-control"/><font color="green">必须是已添加、并通过审核的短信签名。</font></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">商户注册模板ID</label>
	  <div class="col-sm-10"><input type="text" name="sms_tpl_reg" value="<?php echo $conf['sms_tpl_reg']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">找回密码模板ID</label>
	  <div class="col-sm-10"><input type="text" name="sms_tpl_find" value="<?php echo $conf['sms_tpl_find']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <label class="col-sm-2 control-label">修改结算账号模板ID</label>
	  <div class="col-sm-10"><input type="text" name="sms_tpl_edit" value="<?php echo $conf['sms_tpl_edit']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div><br/>
	</div>
  </form>
</div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
978W短信接口：<a href="http://admin.978w.cn" target="_blank" rel="noreferrer">点此进入</a><br/>腾讯云短信接口：<a href="https://console.cloud.tencent.com/sms/smslist" target="_blank" rel="noreferrer">点此进入</a><br/>阿里云短信接口：<a href="https://dysms.console.aliyun.com/dysms.htm" target="_blank" rel="noreferrer">点此进入</a>
</div>
</div>
<script>
$("select[name='sms_api']").change(function(){
	if($(this).val() == 0){
		$("#showAppId").hide();
		$("#showSign").hide();
	}else{
		$("#showAppId").show();
		$("#showSign").show();
	}
});
</script>
<?php
}elseif($mod=='cron'){
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">计划任务设置</h3></div>
<div class="panel-body">
  <form onsubmit="return saveSetting(this)" method="post" class="form-horizontal" role="form">
	<div class="form-group">
	  <label class="col-sm-2 control-label">计划任务访问密钥</label>
	  <div class="col-sm-10"><input type="text" name="cronkey" value="<?php echo $conf['cronkey']; ?>" class="form-control"/></div>
	</div><br/>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 </div>
	</div>
  </form>
</div>
</div>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">回调监控</h3></div>
<div class="panel-body">
<p>以下网址可以设置2-6秒的监控速度,必须监控，已经本地化了，不监控会掉单的</p>
<p>QQ、支付宝监控以下网址(微信无需监控)</p>
<li class="list-group-item">http://<?php echo $_SERVER['HTTP_HOST']?>/Cron.php</li>
<br>
<p>宝塔"计划任务"监控步骤 计划任务->Shell脚本->执行周期改成"1分钟"执行一次->然后脚本内容就是以下</p>
<li class="list-group-item">PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin<br>
export PATH<br>
step=1<br>
for (( i = 0; i < 60; i=(i+step) )); do<br>
curl -sS --connect-timeout 10 -m 60 'http://<?php echo $_SERVER['HTTP_HOST']?>/Cron.php'<br>
echo "-------------------INTLPAY监控成功----------------------"<br>
endDate=`date +"%Y-%m-%d %H:%M:%S"`<br>
echo "★[$endDate] Successful"<br>
echo "-------------------INTLPAY监控成功----------------------"<br>
sleep $step<br>
done<br>
exit 0<br></li>
<img src="/Core/Assets/Img/cron_png.png"  />
</div>
</div>
<?php
}
elseif($mod=='upimg'){
echo '<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">更改首页LOGO</h3></div>
<div class="panel-body">';
if($_POST['s']==1){
if(copy($_FILES['file']['tmp_name'], ROOT.'/Core/Assets/Img/logo.png')){
	echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果，按Ctrl+F5即可一键刷新缓存）";
}else{
	echo "上传失败，可能没有文件写入权限";
}
}
echo '<form action="Set.php?mod=upimg" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="file" id="file" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary btn-block" value="确认上传" /></form><br>现在的图片：<br><img src="/Core/Assets/Img/logo.png?r='.rand(10000,99999).'" style="max-width:100%">';
echo '</div></div>';
}
?>
    </div>
  </div>
<script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
function checkURL(obj)
{
	var url = $(obj).val();

	if (url.indexOf(" ")>=0){
		url = url.replace(/ /g,"");
	}
	if (url.toLowerCase().indexOf("http://")<0 && url.toLowerCase().indexOf("https://")<0){
		url = "http://"+url;
	}
	if (url.slice(url.length-1)!="/"){
		url = url+"/";
	}
	$(obj).val(url);
}
function saveSetting(obj){
	if($("input[name='localurl_alipay']").length>0 && $("input[name='localurl_alipay']").val()!=''){
		checkURL("input[name='localurl_alipay']");
	}
	if($("input[name='localurl_wxpay']").length>0 && $("input[name='localurl_wxpay']").val()!=''){
		checkURL("input[name='localurl_wxpay']");
	}
	if($("input[name='localurl']").length>0 && $("input[name='localurl']").val()!=''){
		checkURL("input[name='localurl']");
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'Ajax.php?act=Set',
		data : $(obj).serialize(),
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.alert('设置保存成功！', {
					icon: 1,
					closeBtn: false
				}, function(){
				  window.location.reload()
				});
			}else{
				layer.alert(data.msg, {icon: 2})
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
	return false;
}
function changeTemplate(template){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'Ajax.php?act=Set',
		data : {template:template},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.alert('更换模板成功！', {
					icon: 1,
					closeBtn: false
				}, function(){
				  window.location.reload()
				});
			}else{
				layer.alert(data.msg, {icon: 2})
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
	return false;
}
</script>