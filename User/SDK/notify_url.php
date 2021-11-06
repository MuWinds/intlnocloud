<?php
include("../../Core/Common.php");
/* *
 * 功能：支付异步通知页面
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 */
//商品名称
$name = $_GET['name'];

$zero_INTL_pid  = $userrow['pid'];
$zero_INTL_key  = $userrow['key'];
$notify_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/notify_url.php";
$return_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/return_url.php";


if(strpos($name,'额度充值') or strpos($name,'开通') or strpos($name,'免挂')){
	$row=$DB->query("SELECT * FROM pay_user WHERE pid='{$conf['zero_pid']}' limit 1")->fetch();
	$zero_INTL_pid  = $row['pid'];
	$zero_INTL_key  = $row['key'];
}
//计算得出通知验证结果
$verify_result = verifyNotify($zero_INTL_key);
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

	$out_trade_no = $_GET['out_trade_no'];

	//支付交易号

	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

	//支付方式
	$type = $_GET['type'];


if ($_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知
	if(strpos($name,'额度充值')){
		$pid = explode('额度充值',$name)[0];
		$name = explode($pid,$name)[1];
		if($name == '额度充值' and $_GET['pid']==$conf['zero_pid']){
			$money = $_GET['money'] * $conf['ed_money'];
			$DB->exec("update `pay_user` set `money`=`money`+'{$money}' where pid='{$pid}'");
		}
	}elseif(strpos($name,'开通') and strpos($name,'免挂')){
		$pid = explode('|',$name)[0];
		$user_row=$DB->query("SELECT * FROM `pay_user` WHERE `pid`='{$pid}' limit 1")->fetch();
		if(strpos($name,'支付宝')){
			$type = 'alipay';
			$name = '支付宝';
			$money = $conf['alipay_free_vip_money'];
		}elseif(strpos($name,'QQ钱包')){
			$type = 'qqpay';
			$name = 'QQ钱包';
			$money = $conf['qqpay_free_vip_money'];
		}else{
			$type = 'wxpay';
			$name = '微信';
			$money = $conf['wxpay_free_vip_money'];
		}
		if($user_row[$type.'_free_vip_time']<$date){
			$vip_time = date('Y-m-d H:i:s',strtotime("+1 month"));//=一个月
		}else{ 
			$vip_time = strtotime($user_row[$type.'_free_vip_time'])-time();
			$vip_time = strtotime(date('Y-m-d H:i:s',strtotime("+1 month")))+$vip_time;
			$vip_time = date('Y-m-d H:i:s',$vip_time);;
		}
		$DB->exec("update `pay_user` set `".$type."_free_vip_time`='{$vip_time}' where pid='{$pid}'");
		echo '开通一个月['.$name.']免挂会员成功'.$money;
	}

}

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";
}
?>