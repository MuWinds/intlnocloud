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


/*
http://intlpay.com/Api.php?act=wxpay_zt_Out&intl_wx_name=%E6%98%B5%E7%A7%B0%E6%B5%8B%E8%AF%95&wx_name=%E6%83%85%E9%9A%BE%E6%8C%BD%E3%80%82&cookie=Login_Yes&authcode=30c1e9e982c520182ce910a22ce5bcd3
*/
require './Core/Common.php'; 
$act=isset($_GET['act'])?$_GET['act']:$_POST['act'];
exit;
$get_authcode = $_GET['authcode']?$_GET['authcode']:$_POST['authcode'];//软件提交过来的验证授权码  查询是否匹配
if($get_authcode != $Authcode)exit("授权码(".$get_authcode.")验证不通过,请勿乱提交参数");//验证授权码

if($act=='pay_zt'){//更新状态
	$qr_id=$_GET['qr_id']?$_GET['qr_id']:$_POST['qr_id'];
	$data_data=$_GET['data_data']?$_GET['data_data']:$_POST['data_data'];//返回信息
	$cookie=$_GET['cookie']?$_GET['cookie']:$_POST['cookie'];//cookie
	$qr_id=urldecode($qr_id);
	$data_data=urldecode($data_data);
	$cookie=urldecode($cookie);
	$status = 0;
	if($cookie){
		$status = 1;
		$data_data = ($data_data?$data_data:'更新成功');
	}else{
		$status = 4;
	}
	$issql = $DB->exec("update `pay_qrlist` set `status`='{$status}',`addtime`='{$date}',`data_data`='{$data_data}',`cookie`='{$cookie}' WHERE `qr_id`='{$qr_id}'");//更新微数据
	if($issql){
		echo '更新状态成功：'.$qr_id;
	}else{
		echo '更新状态失败,可能不存在此二维码';
	}
}elseif($act=='wxpay_zt_Out'){//更新微信状态
	$intl_wx_name=$_GET['intl_wx_name']?$_GET['intl_wx_name']:$_POST['intl_wx_name'];//店员微信名
	$wx_name=$_GET['wx_name']?$_GET['wx_name']:$_POST['wx_name'];//绑定微信名
	$cookie=$_GET['cookie']?$_GET['cookie']:$_POST['cookie'];//cookie
	$intl_wx_name=urldecode($intl_wx_name);
	$wx_name=urldecode($wx_name);
	$cookie=urldecode($cookie);
	$status = 0;
	if($cookie=='Login_Yes'){
		$status = 1;
	}
	$issql = $DB->exec("update `pay_qrlist` set `status`='{$status}',`endtime`='{$date}',`cookie`='{$cookie}',`wx_name`='{$intl_wx_name}' WHERE `beizhu`='{$wx_name}'");//更新微信失效数据
	if($issql){
		echo '更新微信状态成功：'.$wx_name;
	}else{
		echo '更新微信状态失败,可能不存在此微信二维码';
	}
}elseif($act=='Up_Wechat_Trumpet'){//更新微信在线心跳
	$login_time = time()+10;
	$intl_wx_name=$_GET['intl_wx_name']?$_GET['intl_wx_name']:$_POST['intl_wx_name'];//店员微信名
	$intl_wx_name=urldecode($intl_wx_name);
	$issql = $DB->exec("update `pay_wechat_trumpet` set `login_time`='{$login_time}' WHERE `wx_name`='{$intl_wx_name}'");
	if($issql){
		echo '更新微信心跳成功：'.$intl_wx_name;
	}else{
		echo '更新微信心跳失败,可能不存在此微信店员小号';
	}
}else{
	echo '参数错误';
}
?>