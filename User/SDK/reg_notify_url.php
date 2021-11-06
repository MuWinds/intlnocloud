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
//商品名称
$name = $_GET['name'];
if($name=='申请商户' or $name=='%E7%94%B3%E8%AF%B7%E5%95%86%E6%88%B7'){
	$name = '申请商户';
	$row=$DB->query("SELECT * FROM pay_user WHERE pid='{$conf['zero_pid']}' limit 1")->fetch();
	$zero_INTL_pid  = $row['pid'];
	$zero_INTL_key  = $row['key'];
    $money = $conf['reg_pay_price'];//系统设定的金额
	$notify_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/reg_notify_url.php";
    $return_url = "http://".$_SERVER['HTTP_HOST']."/User/SDK/reg_return_url.php";
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
		
		$name = $_GET['name'];
		
		$row=$DB->query("select * from `pay_regcode` where `trade_no`='{$out_trade_no}' order by id desc limit 1")->fetch();
		if($name == '申请商户' and $_GET['pid']==$conf['zero_pid'] and $row['status']!=1){
			
			$user = explode('|',$row['data'])[1];
			$pass = explode('|',$row['data'])[2];
			$email = explode('|',$row['data'])[3];
			$qq = explode('|',$row['data'])[4];
			$phone = explode('|',$row['data'])[5];
			$clientip = explode('|',$row['data'])[6];
			
			$pid='1'.mt_rand(10000000,99999999);
			$key = random(11);
			$money =$conf['reg_money']?$conf['reg_money']:'0.00';
			$sqs=$DB->exec("INSERT INTO `pay_user` (`user`,`pass`,`pid`,`key`,`qq`,`email`,`phone`,`money`,`addtime`) VALUES ('{$user}','{$pass}','{$pid}','{$key}','{$qq}','{$email}','{$phone}','{$money}','{$date}')");
			if(!empty($email)){
				$sub = $conf['sitename'].' - 注册成功通知';
				$msg = '<h2>商户注册成功通知</h2>感谢您注册'.$conf['web_name'].'！<br/>您的登录账号：'.$user.'<br/>您的登录密码：'.$pass.'<br/>您的商户ID：'.$pid.'<br/>您的商户key：'.$key.'<br/>'.$conf['web_name'].'官网：<a href="http://'.$_SERVER['HTTP_HOST'].'/" target="_blank">'.$_SERVER['HTTP_HOST'].'</a><br/>【<a href="'.$siteurl.'" target="_blank">商户管理后台</a>】';
				$result = send_mail($email, $sub, $msg);
			}
			$DB->exec("update `pay_regcode` set `status` ='1' where `id`='{$row['id']}'");
		echo $user;		//请不要修改或删除
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