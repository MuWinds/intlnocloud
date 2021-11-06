<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>INTL即时到账支付</title>
</head>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

require_once('../Core/Common.php');
$trade_no=daddslashes($_GET['trade_no']);
$sitename=base64_decode(daddslashes($_GET['sitename']));
$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$trade_no}' limit 1")->fetch();
if(!$srow)sysmsg('该订单号不存在，请返回来源地重新发起请求！');
$userrow=$DB->query("SELECT * FROM pay_user WHERE pid='{$srow['pid']}' limit 1")->fetch();
if($userrow[$srow['type'].'_pay_open']==2){
/**************************请求参数**************************/
        $notify_url = "http://".$_SERVER['HTTP_HOST']."/Submit/Epay_notify_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = "http://".$_SERVER['HTTP_HOST']."/Submit/Epay_return_url.php";

/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		"pid" => trim($userrow[$srow['type'].'_api_pid']),
		"type" => $srow['type'],
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $trade_no,
		"name"	=> $srow['name'],
		"money"	=> $srow['money'],
		"sitename"	=> $sitename
);

//建立请求
$html_text = submit_pay($parameter,'GET',$userrow[$srow['type'].'_api_key'],$userrow[$srow['type'].'_api_url']);
echo $html_text;
}
?>
</body>
</html>
