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

include("../Core/Common.php");
$act=$_GET['act'];
if($islogin_admin==1 or $act=='Login'){}else exit("<script language='javascript'>window.location.href='./Login.php';</script>");
if($act=='Login'){//登录后台
	$admin_user=daddslashes($_POST['admin_user']);
	$admin_pass=daddslashes($_POST['admin_pass']);
		if(!$admin_user or !$admin_pass){
			$result=array("code"=>-1,"msg"=>"所有参数不能为空");
		}elseif($admin_user==$conf['admin_user'] && $admin_pass==$conf['admin_pass']) {
			$session=md5($admin_user.$admin_pass.$password_hash);
			$token=authcode("{$user}\t{$session}", 'ENCODE', $conf['KEY']);
			setcookie("admin_token", $token, time() + 604800);
			$city=get_ip_city($ip)['Result']['Country'];
			$DB->exec("insert into `pay_log` (`pid`,`type`,`date`,`ip`,`city`) values ('0','后台管理员登陆','".$date."','".$ip."','".$city."')");
			$result=array("code"=>1,"msg"=>"登录成功");
		}elseif($admin_user != $conf['admin_user']) {
			$_SESSION['pass_error']++;
			$result=array("code"=>-1,"msg"=>"登录失败,账号错误");
		}elseif($admin_pass != $conf['admin_pass']) {
			$_SESSION['pass_error']++;
			$result=array("code"=>-1,"msg"=>"登录失败,密码错误");
		}
}elseif($act=='Add_Wechet_Tp'){//添加微信免挂店员		
	$wx_user= $_POST['wx_user'];
	$wx_name= $_POST['wx_name'];
    $beizhu= $_POST['beizhu'];
	$sort= $_POST['sort'];
	$sds=$DB->exec("INSERT INTO `pay_wechat_trumpet` (`wx_user`,`wx_name`,`beizhu`, `sort`, `login_time`,`status`,`addtime`) VALUES ('{$wx_user}','{$wx_name}','{$beizhu}','{$sort}','0','1','{$date}')");
	if($sds){
		$result=array("code"=>1,"msg"=>"添加微信免挂店员成功");
	}else{
		$result=array("code"=>-1,"msg"=>"添加用户失败");
	}
}elseif($act=='Edit_Wechet_Tp'){	//修改微信免挂店员
	$id= $_POST['id'];			
	$wx_user= $_POST['wx_user'];
	$wx_name= $_POST['wx_name'];
    $beizhu= $_POST['beizhu'];
	$sort= $_POST['sort'];
	$status=is_numeric($_POST['status'])?intval($_POST['status']):0;
	$is=$DB->query("SELECT * FROM `pay_wechat_trumpet` WHERE `id`='{$id}' limit 1")->fetch();
	if(!$is){
		$result=array("code"=>-2,"msg"=>"此记录不存在");
	}else{
		$sqs=$DB->exec("update `pay_wechat_trumpet` set `wx_user`='{$wx_user}',`wx_name`='{$wx_name}',`beizhu`='{$beizhu}',`sort` ='{$sort}',`status` ='{$status}' where id='{$id}'");
		if($sqs){
			Add_log('admin','修改微信免挂店员');
			$result=array("code"=>1,"msg"=>"修改微信免挂店员成功");
		}else{
			$result=array("code"=>-1,"msg"=>"修改微信免挂店员失败");
		}
	}
}elseif($act=='Del_Wechet_Tp'){//删除微信店员
	$id=daddslashes($_POST['id']);
	$is=$DB->query("SELECT * FROM `pay_wechat_trumpet` WHERE `id`='{$id}' limit 1")->fetch();
		if(!$is){
			$result=array("code"=>-2,"msg"=>"此记录不存在");
		}else{
			$sql="DELETE FROM `pay_wechat_trumpet` WHERE `id`='{$id}' limit 1";
			if($DB->exec($sql)){
				Add_log('admin','删除微信免挂店员');
				$result=array("code"=>1,"msg"=>"删除微信免挂店员成功");
			}else{
				$result=array("code"=>-1,"msg"=>"删除微信免挂店员失败");
			}
		}
}elseif($act=='WesetStatus'){	//删除修改微信号
	$id=trim($_GET['id']);
	$status=is_numeric($_GET['status'])?intval($_GET['status']):exit('{"code":200}');
	if($status==5){
		if($DB->exec("DELETE FROM `pay_qrlist` WHERE `id`='{$id}'"))
			exit('{"code":200}');
		else
			exit('{"code":400,"msg":"删除失败！['.$DB->error().']"}');
	}elseif($status==0){
		if($DB->exec("update `pay_qrlist` set `status`='{$status}',`cookie`=NULL,`endtime`='{$date}' where `id`='{$id}' limit 1")!==false)
			exit('{"code":200}');
		else
			exit('{"code":400,"msg":"修改失败！['.$DB->error().']"}');
	}elseif($status==1){
		if($DB->exec("update `pay_qrlist` set `status`='{$status}',`cookie`='Login_Yes',`endtime`='{$date}' where `id`='{$id}' limit 1")!==false)
			exit('{"code":200}');
		else
			exit('{"code":400,"msg":"修改失败！['.$DB->error().']"}');
	}else{
		if($DB->exec("update `pay_qrlist` set `status`='{$status}',`endtime`='{$date}' where `id`='{$id}' limit 1")!==false)
			exit('{"code":200}');
		else
			exit('{"code":400,"msg":"修改失败！['.$DB->error().']"}');
	}
}elseif($act=='Weoperation'){	//批量操作微信号
	$status=is_numeric($_POST['status'])?intval($_POST['status']):exit('{"code":-1,"msg":"请选择操作"}');
	$checkbox=$_POST['checkbox'];
	$i=0;
	foreach($checkbox as $id){
	if($status==4)$DB->exec("DELETE FROM `pay_qrlist` WHERE `id`='{$id}'");
	elseif($status==0)$DB->exec("update `pay_qrlist` set `status`='{$status}',`cookie`=NULL,`endtime`='{$date}' where `id`='{$id}' limit 1");
	elseif($status==1)$DB->exec("update `pay_qrlist` set `status`='{$status}',`cookie`='Login_Yes',`endtime`='{$date}' where `id`='{$id}' limit 1");
	else$DB->exec("update `pay_qrlist` set `status`='{$status}',`endtime`='{$date}' where `id`='{$id}' limit 1");
		$i++;
	}
	Add_log('admin','批量操作微信号');
	exit('{"code":0,"msg":"成功改变'.$i.'个微信状态"}');
}elseif($act=='Add_user'){//添加商户
	$pid='1'.mt_rand(10000000,99999999);//商户PID,系统随机给出的		
	$key= $_POST['key'];
    $qq= $_POST['qq'];
	$money= $_POST['money'];
	$sds=$DB->exec("INSERT INTO `pay_user` (`pid`,`key`, `qq`, `money`,`addtime`) VALUES ('{$pid}','{$key}','{$qq}','{$money}','{$date}')");
	if($sds){
		Add_log('admin',"添加用户成功,PID:".$pid);
		$result=array("code"=>1,"msg"=>"添加用户成功,PID:".$pid);
	}else{
		$result=array("code"=>-1,"msg"=>"添加用户失败");
	}
}elseif($act=='Edit_user'){	//修改商户
	$pid= $_POST['pid'];
	$key= $_POST['key'];
    $qq= $_POST['qq'];
    $money= $_POST['money'];
	$alipay_free_vip_time= $_POST['alipay_free_vip_time'].date(" H:i:s");
	$qqpay_free_vip_time= $_POST['qqpay_free_vip_time'].date(" H:i:s");
	$wxpay_free_vip_time= $_POST['wxpay_free_vip_time'].date(" H:i:s");
	$is=$DB->query("SELECT * FROM `pay_user` WHERE `pid`='{$pid}' limit 1")->fetch();
	if(!$is){
		$result=array("code"=>-2,"msg"=>"此用户记录不存在");
	}else{
		$sqs=$DB->exec("update `pay_user` set `key`='{$key}',`qq`='{$qq}',`money` ='{$money}' where pid='{$pid}'");
		if($_POST['alipay_free_vip_time'])$sqs=$DB->exec("update `pay_user` set `alipay_free_vip_time`='{$alipay_free_vip_time}' where pid='{$pid}'");
		if($_POST['qqpay_free_vip_time'])$sqs=$DB->exec("update `pay_user` set `qqpay_free_vip_time`='{$qqpay_free_vip_time}' where pid='{$pid}'");
		if($_POST['wxpay_free_vip_time'])$sqs=$DB->exec("update `pay_user` set `wxpay_free_vip_time`='{$wxpay_free_vip_time}' where pid='{$pid}'");
		if($sqs){
			Add_log('admin',"修改用户成功,PID:".$pid);
			$result=array("code"=>1,"msg"=>"修改用户成功");
		}else{
			$result=array("code"=>-1,"msg"=>"修改用户失败".$DB->errorInfo()[2]);
		}
	}
}elseif($act=='Del_user'){//删除商户
	$pid=daddslashes($_POST['pid']);
	$is=$DB->query("SELECT * FROM `pay_user` WHERE `pid`='{$pid}' limit 1")->fetch();
		if(!$is){
			$result=array("code"=>-2,"msg"=>"此用户记录不存在");
		}else{
			$sql="DELETE FROM `pay_user` WHERE `pid`='{$pid}' limit 1";
			if($DB->exec($sql)){
				$DB->exec("DELETE FROM `pay_qrlist` WHERE `pid`='{$pid}'");
				Add_log('admin',"删除用户成功,PID:".$pid);
				$result=array("code"=>1,"msg"=>"删除用户成功,旗下二维码也一并被清空");
			}else{
				$result=array("code"=>-1,"msg"=>"删除用户失败");
			}
		}
}elseif($act=='Del_Qr'){//删除二维码
	$id=daddslashes($_POST['id']);
	$is=$DB->query("SELECT * FROM `pay_qrlist` WHERE `id`='{$id}' limit 1")->fetch();
		if(!$is){
			$result=array("code"=>-2,"msg"=>"非法操作");
		}else{
			$sql="DELETE FROM `pay_qrlist` WHERE `id`='{$id}' limit 1";
			if($DB->exec($sql)){
				Add_log('admin',"删除二维码成功");
				$result=array("code"=>1,"msg"=>"删除成功");
			}else{
				$result=array("code"=>-1,"msg"=>"删除失败");
			}
		}
}elseif($act=='Qr_shenhe'){	//审核二维码
	$id= $_POST['id'];
	$shenhe_zt= $_POST['shenhe_zt'];
	$is=$DB->query("SELECT * FROM `pay_qrlist` WHERE `id`='{$id}' limit 1")->fetch();
	if(!$is){
		$result=array("code"=>-2,"msg"=>"此二维码记录不存在");
	}else{
		$sqs=$DB->exec("update `pay_qrlist` set `shenhe_zt`='{$shenhe_zt}' where id='{$id}'");
		if($sqs){
			$result=array("code"=>1,"msg"=>"修改成功");
		}else{
			$result=array("code"=>-1,"msg"=>"修改失败");
		}
	}
}elseif($act=='order'){	//订单详细
	$trade_no=trim($_GET['trade_no']);
	$row=$DB->query("select * from pay_order where trade_no='$trade_no' limit 1")->fetch();
	if(!$row)
		exit('{"code":-1,"msg":"当前订单不存在或未成功选择支付通道！"}');
	$result=array("code"=>0,"msg"=>"succ","data"=>$row);
	exit(json_encode($result));
}elseif($act=='setStatus'){	//删除订单
	$trade_no=trim($_GET['trade_no']);
	$status=is_numeric($_GET['status'])?intval($_GET['status']):exit('{"code":200}');
	if($status==5){
		Add_log('admin',"删除订单成功");
		if($DB->exec("DELETE FROM pay_order WHERE trade_no='$trade_no'"))
			exit('{"code":200}');
		else
			exit('{"code":400,"msg":"删除订单失败！['.$DB->error().']"}');
	}else{
		if($DB->exec("update pay_order set status='$status' where trade_no='$trade_no'")!==false)
			exit('{"code":200}');
		else
			exit('{"code":400,"msg":"修改订单失败！['.$DB->error().']"}');
	}
}elseif($act=='notify'){	//重新通知
	$trade_no=trim($_POST['trade_no']);
	$row=$DB->query("SELECT * FROM `pay_order` WHERE `trade_no`='{$trade_no}' limit 1")->fetch();
	if(!$row)
		exit('{"code":-1,"msg":"当前订单不存在！"}');
	$url=creat_callback($row);
	Add_log('admin',"人工总后台回调订单：".$trade_no);
	//if($row['notify']>0)$DB->exec("update pay_order set notify=0 where trade_no='$trade_no'");
	exit('{"code":0,"url":"'.($_POST['isreturn']==1?$url['return']:$url['notify']).'"}');
}elseif($act=='operation'){	//批量操作订单
	$status=is_numeric($_POST['status'])?intval($_POST['status']):exit('{"code":-1,"msg":"请选择操作"}');
	$checkbox=$_POST['checkbox'];
	$i=0;
	foreach($checkbox as $trade_no){
		if($status==4)$DB->exec("DELETE FROM pay_order WHERE trade_no='$trade_no'");
		else $DB->exec("update pay_order set status='$status' where trade_no='$trade_no' limit 1");
		$i++;
	}
	exit('{"code":0,"msg":"成功改变'.$i.'条订单状态"}');
}elseif($act=='Set'){	//修改后台配置信息
	//exit('{"code":0,"msg":"succ'.$_POST['pay_work_name'].'"}');
	foreach($_POST as $k=>$v){
		saveSetting($k, $v);
	}
	$ad=$CACHE->clear();
	Add_log('admin',"修改设置状态");
	if($ad)exit('{"code":0,"msg":"succ"}');
	else exit('{"code":-1,"msg":"修改设置失败"}');
}elseif($act=='setNotice'){	//修改公告状态
	$id=intval($_GET['id']);
	$status=intval($_GET['status']);
	Add_log('admin',"修改公告状态");
	$sql = "UPDATE pay_notice SET status='$status' WHERE id='$id'";
	if($DB->exec($sql))exit('{"code":0,"msg":"修改状态成功！"}');
	else exit('{"code":-1,"msg":"修改状态失败['.$DB->error().']"}');
}elseif($act=='delNotice'){	//删除公告
	$id=intval($_GET['id']);
	$sql = "DELETE FROM pay_notice WHERE id='$id'";
	if($DB->exec($sql))exit('{"code":0,"msg":"删除公告成功！"}');
	else exit('{"code":-1,"msg":"删除公告失败['.$DB->error().']"}');
}elseif($act=='iptype'){	//取IP方式
	$result = [
	['name'=>'0_X_FORWARDED_FOR', 'ip'=>real_ip(0), 'city'=>get_ip_city(real_ip(0))['Result']['Country']],
	['name'=>'1_X_REAL_IP', 'ip'=>real_ip(1), 'city'=>get_ip_city(real_ip(1))['Result']['Country']],
	['name'=>'2_REMOTE_ADDR', 'ip'=>real_ip(2), 'city'=>get_ip_city(real_ip(2)['Result']['Country'])]
	];
	exit(json_encode($result));
}elseif($act=='Pay_set'){	//云端配置信息
	$Instant_url=daddslashes($_POST['Instant_url']);
	$outtime=daddslashes($_POST['outtime']);
	$Instant_pid=daddslashes($_POST['Instant_pid']);
	$Instant_key=daddslashes($_POST['Instant_key']);
		if(!$Instant_url or !$outtime or !$Instant_pid or !$Instant_key){
			$result=array("code"=>-1,"msg"=>"修改失败:所有内容不能留空");
		}else{
			// $Instant_Api=new Instant_Api($Instant_url,$Instant_pid,$Instant_key,$Authcode);
			$Query = ['code' => 1];
			if($Query['code']==1 && $Instant_pid && $Instant_key){
				saveSetting('Instant_url',$_POST['Instant_url']);
				saveSetting('Instant_pid',$_POST['Instant_pid']);
				saveSetting('Instant_key',$_POST['Instant_key']);
				saveSetting('outtime',$_POST['outtime']);		
				$result=array("code"=>1,"msg"=>"修改成功!");
			}elseif($Instant_pid && $Instant_key){
				$result=array("code"=>-1,"msg"=>$Query['msg']."->修改失败,云端API_Pid或Key错误,无法保存配置,请重试！");
			}
		}
}elseif($act=='edit_Huifu'){	//回复工单
	$num=($_POST['num']);	
	$qqmail=($_POST['qq']);	
	$huifu=daddslashes(strip_tags($_POST['huifu']));
	$active=daddslashes(strip_tags($_POST['active']));
		if($active==1 && $qqmail!=''){
		$wdata=date("Y-m-d");
		$sqs=$DB->exec("update `pay_work` set `huifu` ='{$huifu}',`wdata` ='{$wdata}',`active` ='{$active}' where `num`='$num'");
		$qqemail=$qqmail.'@qq.com';
        $siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/';
		$sub = $conf['web_name'].' - 您提交的工单已有回复';
		$msg = '<h2>您的工单已有新的进展</h2>尊敬的商户您好【'.$conf['web_name'].'】对你的工单进行了回复<br/>工单编号为：'.$num.'的工单已经有最新的进展啦！<br/>请尽快登录后台查看详细内容：【<a href="'.$siteurl.'" target="_blank">登录后台</a>】<br/>';
		$result = send_mail($qqemail, $sub, $msg);
		exit('{"code":1,"msg":"succ"}');
		}else{
			$wdata=date("Y-m-d");
		$sqs=$DB->exec("update `pay_work` set `huifu` ='{$huifu}',`wdata` ='{$wdata}',`active` ='{$active}' where `num`='$num'");
		exit('{"code":1,"msg":"succ"}');
		}
}elseif($act=='edit_ShanchuAsk'){	//删除工单
$id=$_POST['ids'];
$asknum=$_POST['asknum'];
$rows=$DB->query("select * from pay_work where uid='$id' AND num='$asknum' limit 1")->fetch();
Add_log('admin',"删除工单");
if(!$rows)
	exit('{"code":-1,"msg":"当前记录不存在！"}');
$urls=explode(',',$rows['url']);
$sql="DELETE FROM pay_work WHERE uid='$id' AND num='$asknum'";	
if($DB->exec($sql))
	 exit('{"code":1,"msg":"succ"}');
else
	 exit('{"code":-1,"msg":"删除失败！"}');
}else{
	$result=array("code"=>-9,"msg"=>"参数错误");
}

if($result)
	exit(json_encode($result));
else
	exit($data);
?>