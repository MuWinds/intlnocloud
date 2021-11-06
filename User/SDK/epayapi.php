<?php
include("../../Core/Common.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>INTL即时到账支付接口</title>
</head>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */
 

/**************************请求参数**************************/

        //商户订单号
        $out_trade_no = $_POST['WIDout_trade_no']?$_POST['WIDout_trade_no']:$_GET['WIDout_trade_no'];
		//付款金额
        $money = $_POST['WIDtotal_fee']?$_POST['WIDtotal_fee']:$_GET['WIDtotal_fee'];
        //必填

		//支付方式
        $type = $_POST['type']?$_POST['type']:$_GET['type'];
		//站点名称
        $sitename = 'IntlPay';
        //订单描述

//商品名称
$name = urldecode($_POST['WIDsubject']?$_POST['WIDsubject']:$_GET['WIDsubject']);

$zero_INTL_pid  = $userrow['pid'];
$zero_INTL_key  = $userrow['key'];
$notify_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/notify_url.php";
$return_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/return_url.php";

if($name=='额度充值'){
	$name = $userrow['pid'].'额度充值';
	$row=$DB->query("SELECT * FROM pay_user WHERE pid='{$conf['zero_pid']}' limit 1")->fetch();
	$zero_INTL_pid  = $row['pid'];
	$zero_INTL_key  = $row['key'];
	$notify_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/notify_url.php";
    $return_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/return_url.php";
	
}elseif($name=='申请商户' or $name=='%E7%94%B3%E8%AF%B7%E5%95%86%E6%88%B7'){
	$name = '申请商户';
	$row=$DB->query("SELECT * FROM pay_user WHERE pid='{$conf['zero_pid']}' limit 1")->fetch();
	$zero_INTL_pid  = $row['pid'];
	$zero_INTL_key  = $row['key'];
    $money = $conf['reg_pay_price'];//系统设定的金额
	$notify_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/reg_notify_url.php";
    $return_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/reg_return_url.php";
}elseif(strpos($name,'续一月') and strpos($name,'免挂会员')){
	$row=$DB->query("SELECT * FROM pay_user WHERE pid='{$conf['zero_pid']}' limit 1")->fetch();
	$zero_INTL_pid  = $row['pid'];
	$zero_INTL_key  = $row['key'];
	if(strpos($name,'支付宝')){
		$name = '支付宝';
		$money = $conf['alipay_free_vip_money'];
	}elseif(strpos($name,'QQ钱包')){
		$name = 'QQ钱包';
		$money = $conf['qqpay_free_vip_money'];
	}else{
		$name = '微信';
		$money = $conf['wxpay_free_vip_money'];
	}
	$name = $userrow['pid'].'|开通'.$name.'免挂';
	$notify_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/notify_url.php";
    $return_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/return_url.php";
}

/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		"pid" => trim($zero_INTL_pid),
		"type" => $type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $out_trade_no,
		"name"	=> $name,
		"money"	=> $money,
		"sitename"	=> $sitename
);

//建立请求
$html_text = submit_pay($parameter,'GET',$zero_INTL_key);
echo $html_text;

?>
正在跳转啊,你眼瞎?
</body>
</html>