<?php
include("../../Core/Common.php");
/* * 
 * 功能：支付页面跳转同步通知页面
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见epay_notify_class.php中的函数verifyReturn
 */

$name = $_GET['name'];

$zero_INTL_pid  = $userrow['pid'];
$zero_INTL_key  = $userrow['key'];
$notify_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/notify_url.php";
$return_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/return_url.php";

$pid = explode('额度充值',$_GET['name'])[0];
$name = explode($pid,$_GET['name'])[1];
if($name=='额度充值'){
	$row=$DB->query("SELECT * FROM pay_user WHERE pid='{$conf['zero_pid']}' limit 1")->fetch();
	$zero_INTL_pid  = $row['pid'];
	$zero_INTL_key  = $row['key'];
}
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//计算得出通知验证结果
$verify_result = verifyNotify($zero_INTL_key);
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号
	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

	//支付方式
	$type = $_GET['type'];


    if($_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }

	echo "开通/充值成功<br/><a href='/User'>返回用户中心";

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    echo "验证失败";
}
?>
        <title>即时到账交易接口</title>
	</head>
    <body>
    </body>
</html>