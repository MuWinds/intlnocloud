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

require_once('../Core/Common.php');
$trade_no=daddslashes($_GET['trade_no']);
$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$trade_no}' limit 1")->fetch();
if($srow['status']==1){
	
	$url=creat_callback($srow);//订单支付成功
	$data=array("backurl"=>$url['return']);
	$result=array("code"=>200,"msg"=>'订单支付成功',"data"=>$data);
	
}elseif($srow['outtime']<=time()){//订单支付超时

	$data=array("backurl"=>'http://'.getdomain($srow['return_url']));
	$result=array("code"=>-1,"msg"=>'订单支付超时',"data"=>$data);
	
}elseif($srow['price']>0.00){//刷出二维码
	
	$QR_row=$DB->query("SELECT * FROM pay_qrlist WHERE id='{$srow['qr_id']}' limit 1")->fetch();
	//你妹的你没事检测余额干啥
	// if($QR_row['status']==1)check_money_notify($QR_row);//检测金额 
	if($srow['type']=='alipay'){
		$userId = getSubstr(base64_decode($QR_row['cookie']), "CLUB_ALIPAY_COM=", ";");//支付宝合作者ID
		//$QR_row['qr_url']='alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data='.urlencode('{"s": "money","u": "'.$userId.'","a": "'.$srow['price'].'","m":"INTLpay"}');
		$QR_row['qr_url']='alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=NO&amount='.$srow['price'].'&userId='.$userId.'&memo=INTL';
	}
	if($srow['type'] =='alipay'){
		$u = '//qrqrppay.aeyo.xyz/qrcode/qrcode_pay.php?ewm='.urlencode($QR_row['qr_url']);
	}else{
		$u = "/".urldecode($QR_row['qr_url']);
	}
	$data=array("qrcode"=>$u,"money"=>$srow['price']);
	$result=array("code"=>100,"msg"=>'请扫码支付',"data"=>$data);
	
}elseif($srow['price']==-0.01){//云端额度不足
	
	$data=array("backurl"=>'http://'.getdomain($srow['return_url']));
	$result=array("code"=>-1,"msg"=>$_SESSION['data_msg'],"data"=>$data);
	
}elseif($srow['apitime']<=(time()-5)){//二维码获取失败

	$data=array("backurl"=>'http://'.getdomain($srow['return_url']));
	$result=array("code"=>-1,"msg"=>'二维码获取失败,云端异常,请稍后重试',"data"=>$data);
	
}elseif(!$srow['price'] or $srow['price']<0.01){
		$trade_no = $trade_no;
		/*
			自动检测订单并通过接口计算订单,让用户秒进入支付界面以及显示二维码
		*/
		usleep(rand(10000,90000));//重新开始 ,微妙为单位， usleep()单位是微秒，1秒 = 1000毫秒 ，1毫秒 = 1000微秒，即1微秒等于百万分之一秒
		ob_flush();
		flush();
		if($trade_no)$Api_Get_Row=$DB->query("SELECT * FROM `pay_order` WHERE `trade_no`='{$trade_no}' limit 1")->fetch();
		//计算订单金额
		$outtime        = ($Api_Get_Row['outtime']-time());
		$pay_id			= daddslashes($Api_Get_Row['pay_id']);
		$trade_no		= daddslashes($Api_Get_Row['trade_no']);
		$out_trade_no	= daddslashes($Api_Get_Row['out_trade_no']);
		$name			= daddslashes(trimall($Api_Get_Row['name'],$Api_Get_Row['pid']));
		$money			= daddslashes($Api_Get_Row['money']);
		$type 			= daddslashes($Api_Get_Row['type']);
		if($Api_Get_Row['type']=='wxpay'){
			$QR_row=$DB->query("SELECT * FROM pay_qrlist WHERE id='{$srow['qr_id']}' limit 1")->fetch();
			$Api_Get_Row['api_type'] = daddslashes(substr(md5(urlencode($QR_row['beizhu'])),8,16));
		}
		$api_type 		= daddslashes($Api_Get_Row['api_type']);
		$notify_url		= daddslashes(($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$conf['local_domain'].'/Submit/Mcode_notify.php');
		$sign			= daddslashes(callback_sign($Api_Get_Row,$name));
		
		//判断是否存在必输入参数，存在并提交

		//这里应该是把订单提交给云端，我们这里不提交，直接伪造一个数组
		$arr = [
			'price' => $money,
			'code' => 1,
		];
		// if($money and $api_type and $Api_Get_Row['price']<0.01)$arr = $Instant_Api->Submit($pay_id,$outtime,$trade_no,$out_trade_no,$notify_url,$api_type,$name,$money,$sign);
		if($arr['price']>0.00 or $arr['code']==1){
			$price = number_format((float)$arr['price'], 2, '.', '');
		}elseif(!$arr['code'] and !$arr){
			$price = '订单作废';
		}elseif($arr['code']==-1 and $arr['price']==-0.01){
			$_SESSION['data_msg'] = $arr['msg'];
			$price = -0.01;
		}else{
			$price = 0.00;
		}
		$price = trim($price);
		$DB->query("update `pay_order` set `price` ='{$price}' where `trade_no`='{$Api_Get_Row['trade_no']}'");
		if($price>0.00){
			$QR_row=$DB->query("SELECT * FROM pay_qrlist WHERE id='{$srow['qr_id']}' limit 1")->fetch();
			if(strstr($QR_row['qr_url'], 'User/QRCODE')){
				$data=array("qrcode"=>"//".$_SERVER['HTTP_HOST']."/".$QR_row['qr_url'],"money"=>$price);
			}else{
				if($srow['type']=='alipay'){
					$userId = getSubstr(base64_decode($QR_row['cookie']), "CLUB_ALIPAY_COM=", ";");//支付宝合作者ID
					//$QR_row['qr_url']='alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data='.urlencode('{"s": "money","u": "'.$userId.'","a": "'.$srow['price'].'","m":"INTLpay"}');
					$QR_row['qr_url']='alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=NO&amount='.$price.'&userId='.$userId.'&memo=INTL';
				}

				if($srow['type'] == 'alipay'){
					$use_url = '//qrqrppay.aeyo.xyz/qrcode/qrcode_pay.php?ewm='.urlencode($QR_row['qr_url']);
				}else{
					$use_url = '/'.urldecode($QR_row['qr_url']);
				}

				$data=array("qrcode"=>$use_url,"money"=>$price);
			}
			$result=array("code"=>100,"msg"=>'请扫码支付',"data"=>$data);
		}else{
			$result=array("code"=>-300,"msg"=>'继续提交...',"data"=>$arr['msg']);
		}
}
exit(json_encode($result));
?>