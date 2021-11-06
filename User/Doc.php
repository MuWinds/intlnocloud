<?php
// +----------------------------------------------------------------------
// | Quotes [ 只为给用户更好的体验]**[我知道发出来有人会盗用,但请您留版权]
// +----------------------------------------------------------------------
// | Licensed ( //www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 零度            盗用不留版权,你就不配拿去!
// +----------------------------------------------------------------------
// | Date: 2019年08月20日
// +----------------------------------------------------------------------

include("../Core/Common.php");
@header('Content-Type: text/html; charset=UTF-8');
//if($islogin_user==1){}else exit("<script language='javascript'>window.location.href='./Login.php';</script>");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8"/>
	<title>开发文档 | <?=$conf['sitename']?>-<?=$conf['title']?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="keywords" content="<?php echo $conf['keywords']?>">
	<meta name="description" content="<?php echo $conf['description']?>">
<meta name="viewport"content="user-scalable=no, width=device-width">
<meta name="viewport"content="width=device-width, initial-scale=1"/>
  	<link rel="shortcut icon" href="../Core/Assets/Img/favicon.ico">
<meta name="renderer"content="webkit">
<!--link rel="stylesheet"href="//template.down.swap.wang/ui/angulr_2.0.1/bower_components/font-awesome/css/font-awesome.min.css"type="text/css"/-->
<link rel="stylesheet"href="//template.down.swap.wang/ui/angulr_2.0.1/bower_components/bootstrap/dist/css/bootstrap.css"type="text/css"/>
<link rel="stylesheet"href="//template.down.swap.wang/template/spay/common.css">
<link rel="stylesheet"href="//template.down.swap.wang/template/spay/index-top.css">
<!--[if IE 9 ]><style type="text/css">#ie9{ display:block; }</style><![endif]-->
<script src="//template.down.swap.wang/ui/angulr_2.0.1/bower_components/jquery/dist/jquery.min.js"></script>
<script src="//template.down.swap.wang/ui/angulr_2.0.1/bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="//template.down.swap.wang/template/spay/jquery-ujs.js"async="true"></script>
<link rel="stylesheet"type="text/css"href="//template.down.swap.wang/template/spay/index.css"/>
<style type="text/css">body{color:#000;}header { position: relative; }</style>
</head>
<body>
<!--[if (gt IE 6)&amp;(lt IE 9)]>
<h1 style='color:red;text-align:center;'>
      你好，浏览器版本过低，升级可正常访问,点击<a style="color:blue"href="//browsehappy.com/">升级您的浏览器</a>
</h1>
<style type="text/css">#ielt9{ display: none; }h1{ height:300px;line-height: 300px;display:block; }header{ display: none; }#ie9{ display: block; }.tenxcloud-logo{ margin:50px auto 0;display:block}</style>
<![endif]--><link rel="stylesheet"href="//template.down.swap.wang/template/spay/common1.css"/>
<script type="text/javascript">

function aclos(){
document.getElementById("q_Msgbox").style.display="none";
}
</script>


<div id="ielt9"style="height:100%">
<header>
<nav id="main-nav"class="navbar navbar-default"role="navigation">
<div class="container">
<div class="row">
<div class="navbar-header">
<button type="button"class="toggle navbar-toggle collapsed"data-toggle="collapse"data-target=".navbar-top-collapse">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a href="/"><span class="logo" style="background:url(assets/img/logo.png) no-repeat;height: 45px"></span></a>
</div>
<div class="navbar-collapse navbar-top-collapse collapse"style="height: 1px;">
<ul class="nav navbar-nav navbar-right c_navbar">

</ul>
<ul class="nav navbar-nav navbar-right z_navbar">
<li><a href="/">首页</a></li>
<li><a href="Doc.php">开发文档</a></li>
<li><a href="index.php">用户中心</a></li>
<!--li><a href="//bbs.buguaiwl.com/forum.php?mod=forumdisplay&fid=49">问题反馈</a></li>
<li class="dropdown"><a class="dropdown-toggle"data-toggle="dropdown" href="/index.php/user/index/">用户中心<b class="caret"></b></a>
<ul  class="dropdown-menu"style="width: 100%;">
<li><a style="line-height:30px;" href="/index.php/index/login/">登陆</a></li>
<li><a style="line-height:30px;" href="/index.php/index/register/">注册</a></li>
</ul>

</li-->
                    
                </ul>
</div>
</div>
</div>
</nav>
</header>

<div id="ie9">你当前的浏览器版本过低，请您升级至IE9以上版本，以达到最佳效果，谢谢！<span class="closeIE">X</span></div>
<div id="scroll_Top">
<i class="fa fa-arrow-up"></i>
<a href="javascript:;"title="去顶部"class="TopTop">TOP</a></div>
<script>

  $('.closeIE').click(function(event) {
    $('#ie9').fadeOut();
  });
</script>

<style type="text/css">
.bann{ content:'';background-size:100%;background:#4280cb;background:-webkit-gradient(linear,0 0,0 100%,from(#4585d2),to(#4280cb));background:-moz-linear-gradient(top,#4585d2,#4280cb);background:linear-gradient(to bottom,#4585d2,#4280cb);top:0;left:0;z-index:-1;min-height:50px;width:100%}.fl .active{ color:#3F5061;background:#fff;border-color:#fff}
</style>

<div class="bann">


<div class="col-xs-12"style="text-align:center;">
<div class="h3"style="color:#ffffff;margin-top: 35px;margin-bottom: 30px;">开发文档</div>
                  
<div style="clear:both;"></div>
</div><div style="clear:both;"></div>
</div>


<div class="container">

  <!-- Docs nav
  ================================================== -->
  <div class="row">
    <div class="col-md-3 ">
      <div id="toc" class="bc-sidebar">
		<ul class="nav">
			<li class="toc-h2"><a href="#toc2">协议规则</a></li>
			<hr/>
			<li class="toc-h2"><a href="#api1">[API]查询商户信息</a></li>
			<li class="toc-h2"><a href="#api4">[API]查询单个订单</a></li>
			<li class="toc-h2"><a href="#api5">[API]批量查询订单</a></li>
			<hr/>
			<li class="toc-h2"><a href="#pay0">发起支付请求</a></li>
			<li class="toc-h2"><a href="#pay1">支付结果通知</a></li>
			<hr/>
			<li class="toc-h2"><a href="#sdk0">SDK下载</a></li>
			<hr/>
		</ul>
	</div>
   </div>

    <div class="col-md-9">
      <article class="post page">
      	<section class="post-content">
<h2 id="toc2">协议规则</h2>
<p>传输方式：HTTP</p>
<p>数据格式：JSON</p>
<p>签名算法：MD5</p>
<p>字符编码：UTF-8</p>
<hr/>
<h2 id="api1">[API]查询商户信息</h2>
<p>URL地址：//<?=$_SERVER['HTTP_HOST']?>/api.php?act=query&amp;pid={商户ID}&amp;key={商户密钥}</p>
<p>请求参数说明：</p>
<table class="table table-bordered table-hover">
  <thead><tr><th>字段名</th><th>变量名</th><th>必填</th><th>类型</th><th>示例值</th><th>描述</th></tr></thead>
  <tbody>
  <tr><td>操作类型</td><td>act</td><td>是</td><td>String</td><td>query</td><td>此API固定值</td></tr>
  <tr><td>商户ID</td><td>pid</td><td>是</td><td>Int</td><td>10001234567890</td><td></td></tr>
  <tr><td>商户密钥</td><td>key</td><td>是</td><td>String</td><td>89unJUB8HZ54Hj7x4nUj56HN4nUzUJ8i</td><td></td></tr>
  </tbody>
</table>
<p>返回结果：</p>
<table class="table table-bordered table-hover">
  <thead><tr><th>字段名</th><th>变量名</th><th>类型</th><th>示例值</th><th>描述</th></tr></thead>
  <tbody>
  <tr><td>返回状态码</td><td>code</td><td>Int</td><td>1</td><td>1为成功，其它值为失败</td></tr>
  <tr><td>商户ID</td><td>pid</td><td>Int(14)</td><td>1001</td><td>所创建的商户ID</td></tr>
  <tr><td>商户密钥</td><td>key</td><td>String(32)</td><td>89unJUB8HZ54Hj7x4nUj56HN4nUzUJ8i</td><td>所创建的商户密钥</td></tr>
  <tr><td>绑定QQ号</td><td>qq</td><td>Int</td><td>1</td><td>商户绑定的腾讯QQ号</td></tr>
  <tr><td>商户状态</td><td>active</td><td>Int</td><td>1</td><td>1为正常，0为封禁</td></tr>
  <tr><td>商户余额</td><td>money</td><td>String</td><td>0.00</td><td>商户所拥有的余额</td></tr>
  <tr><td>结算账号</td><td>account</td><td>String</td><td>pay@cccyun.cn</td><td>结算的支付宝账号</td></tr>
  <tr><td>结算姓名</td><td>username</td><td>String</td><td>张三</td><td>结算的支付宝姓名</td></tr>
  <tr><td>分润比例</td><td>rate</td><td>String</td><td>96</td><td>如果不存在,则默认系统统一的分润比例</td></tr>
  <tr><td>是否实名认证</td><td>issmrz</td><td>Int</td><td>1</td><td>1为认证,其他值则是未认证</td></tr>
  </tbody>
</table>


<h2 id="api4">[API]查询单个订单</h2>
<p>URL地址：//<?=$_SERVER['HTTP_HOST']?>/api.php?act=order&amp;pid={商户ID}&amp;key={商户密钥}&amp;out_trade_no={商户订单号}</p>
<p>请求参数说明：</p>
<table class="table table-bordered table-hover">
  <thead><tr><th>字段名</th><th>变量名</th><th>必填</th><th>类型</th><th>示例值</th><th>描述</th></tr></thead>
  <tbody>
  <tr><td>操作类型</td><td>act</td><td>是</td><td>String</td><td>order</td><td>此API固定值</td></tr>
  <tr><td>商户ID</td><td>pid</td><td>是</td><td>Int</td><td>1001</td><td></td></tr>
  <tr><td>商户密钥</td><td>key</td><td>是</td><td>String</td><td>89unJUB8HZ54Hj7x4nUj56HN4nUzUJ8i</td><td></td></tr>
  <tr><td>商户订单号</td><td>out_trade_no</td><td>是</td><td>String</td><td>20160806151343349</td><td></td></tr>
  </tbody>
</table>
<p>返回结果：</p>
<table class="table table-bordered table-hover">
  <thead><tr><th>字段名</th><th>变量名</th><th>类型</th><th>示例值</th><th>描述</th></tr></thead>
  <tbody>
  <tr><td>返回状态码</td><td>code</td><td>Int</td><td>1</td><td>1为成功，其它值为失败</td></tr>
  <tr><td>返回信息</td><td>msg</td><td>String</td><td>查询订单号成功！</td><td></td></tr>
  <tr><td>易支付订单号</td><td>trade_no</td><td>String</td><td>2016080622555342651</td><td>码支付订单号</td></tr>
  <tr><td>商户订单号</td><td>out_trade_no</td><td>String</td><td>20160806151343349</td><td>商户系统内部的订单号</td></tr>
  <tr><td>支付方式</td><td>type</td><td>String</td><td>alipay</td><td>alipay:支付宝,tenpay:财付通,wxpay:微信支付</td></tr>
  <tr><td>商户ID</td><td>pid</td><td>Int</td><td>1001</td><td>发起支付的商户ID</td></tr>
  <tr><td>创建订单时间</td><td>addtime</td><td>String</td><td>2016-08-06 22:55:52</td><td></td></tr>
  <tr><td>完成交易时间</td><td>endtime</td><td>String</td><td>2016-08-06 22:55:52</td><td></td></tr>
  <tr><td>商品名称</td><td>name</td><td>String</td><td>VIP会员</td><td></td></tr>
  <tr><td>商品金额</td><td>money</td><td>String</td><td>1.00</td><td></td></tr>
  <tr><td>支付状态</td><td>status</td><td>Int</td><td>0</td><td>1为支付成功，0为未支付</td></tr>
  </tbody>
</table>

<h2 id="api5">[API]批量查询订单</h2>
<p>URL地址：//<?=$_SERVER['HTTP_HOST']?>/api.php?act=orders&amp;pid={商户ID}&amp;key={商户密钥}</p>
<p>请求参数说明：</p>
<table class="table table-bordered table-hover">
  <thead><tr><th>字段名</th><th>变量名</th><th>必填</th><th>类型</th><th>示例值</th><th>描述</th></tr></thead>
  <tbody>
  <tr><td>操作类型</td><td>act</td><td>是</td><td>String</td><td>orders</td><td>此API固定值</td></tr>
  <tr><td>商户ID</td><td>pid</td><td>是</td><td>Int</td><td>1001</td><td></td></tr>
  <tr><td>商户密钥</td><td>key</td><td>是</td><td>String</td><td>89unJUB8HZ54Hj7x4nUj56HN4nUzUJ8i</td><td></td></tr>
  <tr><td>查询订单数量</td><td>limit</td><td>否</td><td>Int</td><td>20</td><td>返回的订单数量，最大50</td></tr>
  </tbody>
</table>
<p>返回结果：</p>
<table class="table table-bordered table-hover">
  <thead><tr><th>字段名</th><th>变量名</th><th>类型</th><th>示例值</th><th>描述</th></tr></thead>
  <tbody>
  <tr><td>返回状态码</td><td>code</td><td>Int</td><td>1</td><td>1为成功，其它值为失败</td></tr>
  <tr><td>返回信息</td><td>msg</td><td>String</td><td>查询结算记录成功！</td><td></td></tr>
  <tr><td>订单列表</td><td>data</td><td>Array</td><td></td><td>订单列表</td></tr>
  </tbody>
</table>
<hr/>

<h2 id="pay0">发起支付请求</h2>
<p>URL地址：//<?=$_SERVER['HTTP_HOST']?>/submit.php?pid={商户ID}&amp;type={支付方式}&amp;out_trade_no={商户订单号}&amp;notify_url={服务器异步通知地址}&amp;return_url={页面跳转通知地址}&amp;name={商品名称}&amp;money={金额}&amp;sitename={网站名称}&amp;sign={签名字符串}&amp;sign_type=MD5</p>
<p>请求参数说明：</p>
<table class="table table-bordered table-hover">
  <thead><tr><th>字段名</th><th>变量名</th><th>必填</th><th>类型</th><th>示例值</th><th>描述</th></tr></thead>
  <tbody>
  <tr><td>商户ID</td><td>pid</td><td>是</td><td>Int</td><td>1001</td><td></td></tr>
  <tr><td>支付方式</td><td>type</td><td>是</td><td>String</td><td>alipay</td><td>alipay:支付宝,tenpay:财付通,<br/>qqpay:QQ钱包,wxpay:微信支付</td></tr>
  <tr><td>商户订单号</td><td>out_trade_no</td><td>是</td><td>String</td><td>20160806151343349</td><td></td></tr>
  <tr><td>异步通知地址</td><td>notify_url</td><td>是</td><td>String</td><td>//www.cccyun.cc/notify_url.php</td><td>服务器异步通知地址</td></tr>
  <tr><td>跳转通知地址</td><td>return_url</td><td>是</td><td>String</td><td>//www.cccyun.cc/return_url.php</td><td>页面跳转通知地址</td></tr>
  <tr><td>商品名称</td><td>name</td><td>是</td><td>String</td><td>VIP会员</td><td></td></tr>
  <tr><td>商品金额</td><td>money</td><td>是</td><td>String</td><td>1.00</td><td></td></tr>
  <tr><td>网站名称</td><td>sitename</td><td>否</td><td>String</td><td>彩虹云任务</td><td></td></tr>
  <tr><td>签名字符串</td><td>sign</td><td>是</td><td>String</td><td>202cb962ac59075b964b07152d234b70</td><td>签名算法与<font color=red><a href="//doc.open.alipay.com/docs/doc.htm?treeId=62&articleId=104741&docType=1" target="_blank">支付宝签名算法</a></font>相同<br>(money={商品金额}&name={商品名称}& notify_url={异步通知地址}&out_trade_no={商户订单号}&pid={商户ID}&return_url={同步通知地址}&sitename={站点名称}&type={支付方式}{商户密匙})</td></tr>
  <tr><td>签名类型</td><td>sign_type</td><td>是</td><td>String</td><td>MD5</td><td>默认为MD5</td></tr>
  </tbody>
</table>

<h2 id="pay1">支付结果通知</h2>
<p>通知类型：服务器异步通知（notify_url）、页面跳转通知（return_url）</p>
<p>请求方式：GET</p>
<p>请求参数说明：</p>
<table class="table table-bordered table-hover">
  <thead><tr><th>字段名</th><th>变量名</th><th>必填</th><th>类型</th><th>示例值</th><th>描述</th></tr></thead>
  <tbody>
  <tr><td>商户ID</td><td>pid</td><td>是</td><td>Int</td><td>1001</td><td></td></tr>
  <tr><td>易支付订单号</td><td>trade_no</td><td>是</td><td>String</td><td>20160806151343349021</td><td>码支付订单号</td></tr>
  <tr><td>商户订单号</td><td>out_trade_no</td><td>是</td><td>String</td><td>20160806151343349</td><td>商户系统内部的订单号</td></tr>
  <tr><td>支付方式</td><td>type</td><td>是</td><td>String</td><td>alipay</td><td>alipay:支付宝,qqpay:QQ钱包,wxpay:微信支付</td></tr>
  <tr><td>商品名称</td><td>name</td><td>是</td><td>String</td><td>VIP会员</td><td>PS:可能会过滤空格或违法名称</td></tr>
  <tr><td>商品金额</td><td>money</td><td>是</td><td>String</td><td>1.00</td><td>PS:实际付款金额可能跟商品金额不一致</td></tr>
  <tr><td>支付状态</td><td>trade_status</td><td>是</td><td>String</td><td>TRADE_SUCCESS</td><td></td></tr>
  <tr><td>签名字符串</td><td>sign</td><td>是</td><td>String</td><td>202cb962ac59075b964b07152d234b70</td><td>签名算法与<font color=red><a href="//doc.open.alipay.com/docs/doc.htm?treeId=62&articleId=104741&docType=1" target="_blank">支付宝签名算法</a></font>相同</td><br>判断   MD5(money={支付金额}&name=测试商品&out_trade_no={商户订单号}&pid={商户ID}&trade_no={支付平台订单号}&trade_status=TRADE_SUCCESS&type={支付方式})   是否跟  返回签名  一致</tr>
  <tr><td>签名类型</td><td>sign_type</td><td>是</td><td>String</td><td>MD5</td><td>默认为MD5</td></tr>
  </tbody>
</table>
<hr/>
<h2 id="sdk0">SDK下载</h2>
<blockquote>
<a href="./plug-in/Php_Sdk.zip" style="color:blue">Php_Sdk.zip</a><br/>
SDK版本：V1.0
</blockquote>

          </section>
      </article>
    </div>
  </div>

</div>


<div class="address">
<footer>
<div class="container">
<div class="row">
<div class="col-xs-12 col-md-8 col-lg-9">
<ul class="porduct">
<h4>合作伙伴</h4>
<li><a href="/" target="_blank">本站</a></li>
<li><a href="/" target="_blank">本站</a></li>
</ul>
<ul class="price">
<h4>关于我们</h4>
<li><?=$conf['sitename']?>是零度网络科技有限公司旗下的免签约支付产品</li>
</ul>
<ul class="about"style="width: 40%;padding-left: 22px;">
<h4>联系我们</h4>
<li><strong>QQ:</strong><a target="_blank"href="//wpa.qq.com/msgrd?v=3&amp;uin=<?=$userrow['qq']?>&amp;site=qq&amp;menu=yes"><?=$userrow['qq']?></a></li>
<li><strong>Email:</strong><a name="baidusnap4"></a><a href="mailto:<?=$userrow['qq']?>@qq.com"><?=$userrow['qq']?>@qq.com</a></li>
</ul>
</div>

</div>
<div class="xinxi">
<p>Copyright © 2018 <?=$conf['sitename']?>  | Powered by </p>