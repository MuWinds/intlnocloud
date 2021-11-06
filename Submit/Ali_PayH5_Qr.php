

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>请稍后...</title>
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <style type="text/css">
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background: #ffffff;
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
            overflow: hidden;
        }
    </style>
    <style>
        .demo {
            margin: 1em 0;
            padding: 1em 1em 2em;
            background: #fff;
        }

        .demo h1 {
            padding-left: 8px;
            font-size: 24px;
            line-height: 1.2;
            border-left: 3px solid #108EE9;
        }

        .demo h1,
        .demo p {
            margin: 1em 0;
        }

        .demo .am-button + .am-button,
        .demo .btn + .btn,
        .demo .btn:first-child {
            margin-top: 10px;
        }

        .fn-hide {
            display: none !important;
        }

        input {
            display: block;
            padding: 4px 10px;
            margin: 10px 0;
            line-height: 28px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
 		<script type="text/javascript" src="Template/default/assets/js/jquery.min.js"></script>
		<script src="//cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<?php
header("Content-type: text/html; charset=utf-8");

require_once('../Core/Common.php');
$trade_no=daddslashes($_GET['trade_no']);
$t = daddslashes($_GET['t']);
$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$trade_no}' limit 1")->fetch();
$QR_row=$DB->query("SELECT * FROM pay_qrlist WHERE id='{$srow['qr_id']}' limit 1")->fetch();
if(!$srow)sysmsg('该订单号不存在，请返回来源地重新发起请求！');


if (strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay') !== false ) {
  if(empty($_GET['price'])){
	  

        //echo"请转编码访问！";
    }else{
	
 ?>
<script>	
var qr_url = ('<?=$QR_row["qr_url"]?>');//支付金额
AlipayJSBridge.call('setTitleColor',{color:parseInt('c14443',16),reset:false});
AlipayJSBridge.call('showTitleLoading');
AlipayJSBridge.call('setTitle',{title:'请稍等..',subtitle:'检测支付环境..'});
AlipayJSBridge.call('setOptionMenu',{icontype:'filter',redDot:'01',});
AlipayJSBridge.call('showOptionMenu');
document.addEventListener('optionMenu',function(e){AlipayJSBridge.call('showPopMenu',{menus:[{name:'查看帮助',tag:'tag1',redDot:''},{name:'呵呵',tag:'tag2',}],},function(e){console.log(e)})},false);
function javascrip(){history.go(0)}
window.location.href=encodeURI(qr_url);
//AlipayJSBridge.call('exitApp');
</script>
<center> <h3><small style="color:blue; font-size:20px" id="divTime">正在生成订单,请稍等一会儿哦...</small> <br/></h3></center>
<?php
	exit;
	}
	
}

?>
<?php 
$PHP_SELF = $_SERVER["SCRIPT_NAME"]?$_SERVER["SCRIPT_NAME"]:$_SERVER["PHP_SELF"];
$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/'.$PHP_SELF;
	
//$payurl="https://qr.alipay.com/fkx19853mou0hriqlbg3z86";
//$payurl='alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data='.urlencode('{"s": "money","u": "2088822844096304","a": "1.88","m":"1=8888"}');
$payurl=$siteurl."?price=".$srow['price']."&trade_no=".$trade_no;
//$payurl="http://47.98.149.217/10.php?price=1.00&userId=2088222129248934&memo=q522992946";
//echo $siteurl;
?>
						<center> <h3><small style="color:blue; font-size:20px" id="divTime">正在生成订单,请稍等...</small> <br/></h3></center> 

<script>
var trade_no = ('<?=$trade_no?>');//支付金额
var t = ("<?=$_GET['t']?>");//支付金额
var t = Date.parse(new Date());
var trade_no = ('<?=$trade_no?>')+t;//支付金额

       var intDiff = parseInt('3');//倒计时总秒数量
       var price = ('<?=$price?>');//支付金额
       var payurl = ('<?=$payurl?>');//支付链接
if(!$.cookie(trade_no)){
				var cookietime = new Date(); 
				cookietime.setTime(cookietime.getTime() + (10*60*1000));
				$.cookie(trade_no, false, { expires: cookietime });
				Pay_location(payurl); 
}	
     function timer(intDiff){
         window.setInterval(function(){
         var day=0,
             hour=0,
             minute=0,
             second=0;//时间默认值       
         if(intDiff > 0){
         	day = Math.floor(intDiff / (60 * 60 * 24));
             hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
             minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
         }
		if (minute <= 9) minute = '0' + minute;
		if (second <= 9) second = '0' + second;
		if (hour <= 0 && minute <= 0 && second <= 0) {
			timer(intDiff);
			if(!$.cookie(trade_no)){
				var cookietime = new Date(); 
				cookietime.setTime(cookietime.getTime() + (10*60*1000));
				$.cookie(trade_no, false, { expires: cookietime });
				Pay_location(payurl); 
			}
		}else{
			$("#divTime").html("支付倒计时:<small style='color:red; font-size:20px'>" + minute + "</small>分<small style='color:red; font-size:26px'>" + second + "</small>秒<br>务必付款<small style='color:red; font-size:20px'>" + price + "</small>元,不可以多或少<br>请耐心等待跳转付款页面<br>若无法跳转到支付页面请选其他方式<br>若无法跳转到支付页面请选其他方式<br>若无法跳转到支付页面请选其他方式");
		}
		intDiff--
		}, 1000);
     } 
	 
     $(function(){
         timer(intDiff);
     });
	 
AlipayJSBridge.call("setTitleColor",{color:parseInt('c14443',16),reset:false});
AlipayJSBridge.call('showTitleLoading');
AlipayJSBridge.call('setTitle',{title:'请稍等..',subtitle:'检测支付环境..'});
AlipayJSBridge.call('setOptionMenu',{icontype:'filter',redDot:'01',});
AlipayJSBridge.call('showOptionMenu');
document.addEventListener('optionMenu',function(e){AlipayJSBridge.call('showPopMenu',{menus:[{name:"查看帮助",tag:"tag1",redDot:""},{name:"呵呵",tag:"tag2",}],},function(e){console.log(e)})},false);
function javascrip(){history.go(0)}
function Pay_location(pay_url){ 
var a="58107625";
var b="33336948";
var c="77653371";
var d="41848532";
var e="49796345";
var f="75819150";
//var u="https://qr.alipay.com/fkx19853mou0hriqlbg3z86";
var u=pay_url;
var t=g(a,b,c,d,e,f);
u = encodeURIComponent(u)+'&t='+encodeURIComponent(t);//encodeURI encodeURIComponent +encodeURI('alipays://platformapi/startapp?saId=10000007&clientVersion=3.7.0.0718&qrcode=')
//'alipays://platformapi/startapp?saId=10000007&clientVersion=3.7.0.0718&qrcode='+
window.location.href=encodeURI('alipays://platformapi/startapp?appId=20000067&url=')+u;//
function g(a,b,c,d,e,f){
	a=parseInt(a);
	b=parseInt(b);
	c=parseInt(c);
	d=parseInt(d);
	e=parseInt(e);
	f=parseInt(f);
	for(var i=0;i<5;i++){
		a=a+b;b=b+c;c=c+d;d=d+e;e=e+f;f=f+a
	}
	for(var j=0;j<50;j++){
		debugger
	}
	return a+b+c+d+e+f
	}
}
	</script>
</html>
