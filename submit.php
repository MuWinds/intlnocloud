<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>正在为您跳转到支付页面，请稍候...</title>
    <style type="text/css">
        body {margin:0;padding:0;}
        p {position:absolute;
            left:50%;top:50%;
            width:500px;height:50px;
            margin:-60px 0 0 -260px;
            padding:50px;font:bold 28px/60px "宋体", Arial;
            background:#f9fafc url(Core/Assets/Lmg/load.gif) no-repeat 20px 26px;
            text-indent:22px;border:1px solid #c5d0dc;}
        #waiting {font-family:Arial;}
    </style>
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
require './Core/Common.php'; 
@header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['pid'])){
	$queryArr=$_GET;
}else{
	$queryArr=$_POST;
}

$pid=intval($queryArr['pid']);
if(empty($pid))sysmsg('PID不存在');
$userrow=$DB->query("SELECT * FROM pay_user WHERE pid='{$pid}' limit 1")->fetch();
//$prestr=createLinkstring(argSort(paraFilter($queryArr)));
//if(!md5Verify($prestr, $queryArr['sign'], $userrow['key']))sysmsg('签名校验失败，请返回重试！'.$pid);
//sysmsg(submit_sign($queryArr,$queryArr['sign'],$userrow['key']).$prestr);
if(!submit_sign($queryArr,$queryArr['sign'],$userrow['key']))sysmsg('PID:'.$pid.'签名校验失败，请检测好PID/KEY后返回重试！');


$type=daddslashes($queryArr['type']);
$out_trade_no=daddslashes($queryArr['out_trade_no']);
$notify_url=strip_tags(daddslashes($queryArr['notify_url']));
$return_url=strip_tags(daddslashes($queryArr['return_url']));
$name=strip_tags(daddslashes($queryArr['name']));
$money=daddslashes($queryArr['money']);
$sitename=urlencode(base64_encode(daddslashes($queryArr['sitename'])));


if(empty($out_trade_no))sysmsg('订单号(out_trade_no)不能为空');
if(empty($notify_url))sysmsg('通知地址(notify_url)不能为空');
if(empty($return_url))sysmsg('回调地址(return_url)不能为空');
if(empty($name))sysmsg('商品名称(name)不能为空');
if(empty($money))sysmsg('金额(money)不能为空');
if($conf['pay_maxmoney']>0 && $money>$conf['pay_maxmoney'])sysmsg('最大支付金额是'.$conf['pay_maxmoney'].'元');
if($conf['pay_minmoney']>0 && $money<$conf['pay_minmoney'])sysmsg('最小支付金额是'.$conf['pay_minmoney'].'元');
if($money<=0 || !is_numeric($money))sysmsg('金额不合法');
if(!preg_match('/^[a-zA-Z0-9.\_\-]+$/',$out_trade_no))sysmsg('订单号(out_trade_no)格式不正确');
if($userrow['money']<$money)sysmsg('当前商户额度不足['.$money.']，所以无法生成此订单,请商户们自行进行充值');
if($userrow[$type.'_free_vip_time']<$date)sysmsg('当前商户未开通此通道会员，所以无法生成此订单,请商户们自行进商户中心开通');
$domain=getdomain($notify_url);
if(!empty($conf['blockname'])){
	$block_name = explode('|',$conf['blockname']);
	foreach($block_name as $rows){
		if(strpos($name,$rows)!==false){
			$DB->query("insert into `pay_risk` (`pid`,`url`,`content`,`date`) values ('".$pid."','".$domain."','".$rows."','".$date."')");
			sysmsg($conf['blockalert']?$conf['blockalert']:'该商品禁止出售');
		}
	}
}
	$outtime=time();//获取当前时间戳
	//判断是否存在此订单号,如果存在则更新,不存在则写入
	$is_=$DB->query("SELECT * FROM pay_order WHERE out_trade_no='{$out_trade_no}' and outtime>'{$outtime}' limit 1")->fetch();
if($is_ and $is_['status']=='0'){
	$trade_no=$is_['trade_no'];
	$apitime=time()+8;//获取当前时间戳
	$DB->query("update `pay_order` set `apitime` ='{$apitime}',`type`='{$type}',`status`='{$status}',`price`='0.00' where `trade_no`='{$is_['trade_no']}'");
}else{
	$qr_nums=$DB->query("SELECT count(*) from pay_order WHERE type='{$type}' and pay_id='{$ip}' and outtime>'{$outtime}'")->fetchColumn();
	if($qr_nums>=3)sysmsg('操作频繁，请'.$conf['outtime'].'秒后重试！');
	
	$outtime=time();//获取当前时间戳
	
	
	if($userrow[$type.'_pay_open']==0){
		//开始顺序轮询二维码（选择调用次数最小的）
		$QR_row=$DB->query("SELECT * FROM `pay_qrlist` WHERE `type`='{$type}' and `status`='1' and `pid`='{$userrow['pid']}' order by nums asc limit 1")->fetch();
		//是否有可用二维码
		if(!$QR_row){
			sysmsg('<h2>'.($type == 'alipay'?'支付宝':($type == 'qqpay'?'QQ钱包':'微信')).'支付下单失败,暂无收款账户,或掉线<h2>');
		}
	}else{//开始顺序轮询二维码（选择调用次数最小的）
		$QR_row=$DB->query("SELECT * FROM `pay_qrlist` WHERE `type`='{$type}' and `pid`='{$userrow['pid']}' order by nums asc limit 1")->fetch();
		//是否有可用二维码
		if(!$QR_row){
			sysmsg('<h2>'.($type == 'alipay'?'支付宝':($type == 'qqpay'?'QQ钱包':'微信')).'支付下单失败,暂无任何收款账户<h2>');
		}
		if($type=='wxpay')sysmsg('<h2>'.($type == 'alipay'?'支付宝':($type == 'qqpay'?'QQ钱包':'微信')).'支付下单失败,暂无任何收款账户<h2>');
	}
	
	//记录设备调用排序
	$DB->exec("update `pay_qrlist` set `nums` =`nums`+'1' where `id`='{$QR_row['id']}'");
	
	//最后一张可用的二维码
	$QR_is=$DB->query("SELECT * FROM `pay_qrlist` WHERE `status`='1' and `type`='{$type}' and `pid`='{$userrow['pid']}' order by addtime desc limit 1")->fetch();
	
	//调用到最后一个设备可用后重置所有二维码调用排序次数
	if($QR_row['id']==$QR_is['id'])$DB->exec("update `pay_qrlist` set `nums`='0' WHERE `type`='{$type}' and `pid`='{$userrow['pid']}'");

	$api_type = $userrow['pid'];//减少并发递增金额
	$api_type  = daddslashes(substr(md5(urlencode($_SERVER['HTTP_HOST'].'_'.$api_type)),8,10)).'_'.$type;
	
	
	$outtime=$userrow['outtime']?$userrow['outtime']:$conf['outtime'];//订单过期时间设定
	$outtime=time()+($outtime>180?$outtime:180);
	
	$trade_no=date("YmdHis").rand(11111,99999);
	$apitime=time()+10;//超过此时间则放弃此二维码
	if(!$DB->query("insert into `pay_order` (`trade_no`,`out_trade_no`,`notify_url`,`return_url`,`type`,`pid`,`addtime`,`name`,`money`,`qr_id`,`price`,`pay_id`,`api_type`,`apitime`,`outtime`,`status`) values ('".$trade_no."','".$out_trade_no."','".$notify_url."','".$return_url."','".$type."','".$pid."','".$date."','".$name."','".$money."','".$QR_row['id']."','0.00','".$ip."','".$api_type."','".$apitime."','".$outtime."','0')"))sysmsg('创建订单失败，请返回重试！');
	
}

echo'<p>正在为您跳转到支付页面，请稍候...</p></body></html>';

if($QR_row['status']==0 and $userrow[$type.'_pay_open']==2)
{
	$DB->query("update `pay_order` set `price` ='{$money}' where `trade_no`='{$trade_no}'");
	exit("<script>window.location.href='Submit/Epay_Api.php?trade_no={$trade_no}&sitename={$sitename}';</script>");
}

if($type){
	exit("<script>window.location.href='Submit/Mcode_Pay.php?trade_no={$trade_no}&sitename={$sitename}';</script>");
}else{
	echo "<script>window.location.href='Submit/default.php?trade_no={$trade_no}&sitename={$sitename}';</script>";
}

?>