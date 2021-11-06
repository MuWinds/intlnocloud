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
//if($islogin_user==1 or $act=='Login'){}else exit(json_encode(array("code"=>-5,"msg"=>"未登录")));
if($act=='Retrieve'){//找回商户信息
	$qq=$_POST["qq"]?$_POST["qq"]:$_GET["qq"];
	$email=$qq.'@qq.com';
	$isqq=$DB->query("SELECT * FROM pay_user WHERE qq='{$qq}' limit 1")->fetch();
	$sub = $conf['sitename'].' - 找回密码';
	$msg = '您的PID是：'.$isqq['pid'].'  Key：'.$isqq['key'];
	if(!$qq){
			$result=array("code"=>-2,"msg"=>"请输入QQ号");
	}elseif($isqq){
			$result=array("code"=>-2,"msg"=>"找回失败,此QQ不存在数据库中");
	}else{
		$send_res = send_mail($email, $sub, $msg);
		if($send_res==true&&$_SESSION["code"]){
			$result=array("code"=>1,"msg"=>"商户PID KEY已发送,请进入QQ邮箱查看");
		}else{
			$result=array("code"=>-1,"msg"=>"发送失败,邮箱配置不正确");
		}
	}
}elseif($act=='Captcha'){//滑动验证
	$GtSdk = new \lib\GeetestLib($conf['CAPTCHA_ID'], $conf['PRIVATE_KEY']);
	$data = array(
		'user_id' => isset($uid)?$uid:'public', # 网站用户id
		'client_type' => "web", # web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
		'ip_address' => $ip # 请在此处传输用户请求验证时所携带的IP
	);
	$status = $GtSdk->pre_process($data, 1);
	$_SESSION['gtserver'] = $status;
	$_SESSION['user_id'] = isset($uid)?$uid:'public';
	exit($GtSdk->get_response_str());
}elseif($act=='Sendcode'){//获取注册验证码

	$sendto=daddslashes(htmlspecialchars(strip_tags(trim($_POST['sendto']))));
	if($conf['reg_open']==0)exit('{"code":-1,"msg":"未开放商户申请"}');
	if(isset($_SESSION['send_mail']) && $_SESSION['send_mail']>time()-10){
		exit('{"code":-1,"msg":"请勿频繁发送验证码"}');
	}

	$GtSdk = new \lib\GeetestLib($conf['CAPTCHA_ID'], $conf['PRIVATE_KEY']);

	$data = array(
		'user_id' => 'public', # 网站用户id
		'client_type' => "web", # web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
		'ip_address' => $ip # 请在此处传输用户请求验证时所携带的IP
	);

	if ($_SESSION['gtserver'] == 1) {   //服务器正常
		$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
		if ($result) {
			//echo '{"status":"success"}';
		} else{
			exit('{"code":-1,"msg":"验证失败，请重新验证"}');
		}
	}else{  //服务器宕机,走failback模式
		if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
			//echo '{"status":"success"}';
		}else{
			exit('{"code":-1,"msg":"验证失败，请重新验证"}');
		}
	}

	if($conf['verifytype']==1){
		$phone = $sendto;
		$row=$DB->query("SELECT * FROM pay_user WHERE phone='{$phone}' limit 1")->fetch();
		if($row){
			exit('{"code":-1,"msg":"该手机号已经注册过商户，如需找回商户信息，请返回登录页面点击找回商户"}');
		}
		$row=$DB->query("SELECT * FROM pay_regcode WHERE `to`='{$phone}' order by id desc limit 1")->fetch();
		if($row['time']>time()-60){
			exit('{"code":-1,"msg":"两次发送短信之间需要相隔60秒！"}');
		}
		$count=$DB->query("select count(*) from pay_regcode where `to`='$phone' and time>'".(time()-3600*24)."'")->fetchColumn();
		if($count>2){
			exit('{"code":-1,"msg":"该手机号码发送次数过多，请更换号码！"}');
		}
		$count=$DB->query("select count(*) from pay_regcode where ip='$ip' and time>'".(time()-3600*24)."'")->fetchColumn();
		if($count>5){
			exit('{"code":-1,"msg":"你今天发送次数过多，已被禁止注册"}');
		}
		$code = rand(111111,999999);
		$result = send_sms($phone, $code, 'reg');
		if($result===true){
			if($DB->exec("insert into `pay_regcode` (`type`,`code`,`to`,`time`,`ip`,`status`) values ('1','".$code."','".$phone."','".time()."','".$ip."','0')")){
				$_SESSION['send_mail']=time();
				exit('{"code":0,"msg":"succ"}');
			}else{
				exit('{"code":-1,"msg":"写入数据库失败。'.$DB->error().'"}');
			}
		}else{
			exit('{"code":-1,"msg":"短信发送失败 '.$result.'"}');
		}
	}else{
		$email = $sendto;
		$row=$DB->query("SELECT * FROM pay_user WHERE email='{$email}' limit 1")->fetch();
		if($row){
			exit('{"code":-1,"msg":"该邮箱已经注册过商户，如需找回商户信息，请返回登录页面点击找回商户"}');
		}
		$row=$DB->query("SELECT * FROM pay_regcode WHERE `to`='{$email}' order by id desc limit 1")->fetch();
		if($row['time']>time()-60){
			exit('{"code":-1,"msg":"两次发送短信之间需要相隔60秒！"}');
		}
		$count=$DB->query("select count(*) from pay_regcode where `to`='$email' and time>'".(time()-3600*24)."'")->fetchColumn();
		if($count>6){
			exit('{"code":-1,"msg":"该邮箱发送次数过多，请更换邮箱！"}');
		}
		$count=$DB->query("select count(*) from pay_regcode where ip='$ip' and time>'".(time()-3600*24)."'")->fetchColumn();
		if($count>10){
			exit('{"code":-1,"msg":"你今天发送次数过多，已被禁止注册"}');
		}
		$sub = $conf['sitename'].' - 验证码获取';
		$code = rand(1111111,9999999);
		$msg = '您的验证码是：'.$code;
		$result = send_mail($email, $sub, $msg);
		if($result==true){
			if($DB->exec("insert into `pay_regcode` (`type`,`code`,`to`,`time`,`ip`,`status`) values ('0','".$code."','".$email."','".time()."','".$ip."','0')")){
				$_SESSION['send_mail']=time();
				exit('{"code":0,"msg":"succ"}');
			}else{
				exit('{"code":-1,"msg":"写入数据库失败。'.$DB->error().'"}');
			}
		}else{
			file_put_contents('mail.log',$result);
			exit('{"code":-1,"msg":"邮件发送失败"}');
		}
	}
}elseif($act=='Reg'){//注册商户
	$user=trim(strip_tags(daddslashes($_POST['user'])));
	$pass=trim(strip_tags(daddslashes($_POST['pass'])));
    $qq=trim(strip_tags(daddslashes($_POST['qq'])));
	$email=trim(strip_tags(daddslashes($_POST['email'])));
	$phone=trim(strip_tags(daddslashes($_POST['phone'])));
	$code=trim(strip_tags(daddslashes($_POST['code'])));
	
	if($conf['reg_open']==0)exit('{"code":-1,"msg":"未开放商户申请"}');
	/*
	if(isset($_SESSION['reg_submit']) && $_SESSION['reg_submit']>time()-600){
		exit('{"code":-1,"msg":"请勿频繁注册"}');
	}
	*/
	if($conf['verifytype']==1){
		$row=$DB->query("select * from pay_user where phone='$phone' limit 1")->fetch();
		if($row){
			exit('{"code":-1,"msg":"该手机号已经注册过商户，如需找回商户信息，请返回登录页面点击找回商户"}');
		}
	}
	$row=$DB->query("select * from pay_user where user='$user' limit 1")->fetch();
	if($row){
		exit('{"code":-1,"msg":"该用户名已存在，如需找回商户信息，请返回登录页面点击找回商户"}');
	}
	$row=$DB->query("select * from pay_user where email='$email' limit 1")->fetch();
	if($row){
		exit('{"code":-1,"msg":"该邮箱已经注册过商户，如需找回商户信息，请返回登录页面点击找回商户"}');
	}
	$row=$DB->query("select * from pay_user where qq='$qq' limit 1")->fetch();
	if($row){
		exit('{"code":-1,"msg":"当前QQ已存在，如需找回商户信息，请返回登录页面点击找回商户"}');
	}
	if(strlen($user)<6 or strlen($pass)<6){
		exit('{"code":-1,"msg":"请填写6位以上的账号密码！"}');
	}
	if($conf['verifytype']==0 && !preg_match('/^[A-z0-9._-]+@[A-z0-9._-]+\.[A-z0-9._-]+$/', $email)){
		exit('{"code":-1,"msg":"邮箱格式不正确"}');
	}
	if($conf['verifytype']==1){
		$row=$DB->query("select * from `pay_regcode` where `type`='1' and `code`='{$code}' and `to`='{$phone}' order by id desc limit 1")->fetch();
	}else{
		$row=$DB->query("select * from `pay_regcode` where `type`='0' and `code`='{$code}' and `to`='{$email}' order by id desc limit 1")->fetch();
	}
	if(!$row){
		exit('{"code":-1,"msg":"验证码不正确！"}');
	}
	if($row['time']<time()-3600 || $row['status']>0){
		exit('{"code":-1,"msg":"验证码已失效，请重新获取"}');
	}
	$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
	$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
	$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';
	if($conf['reg_pay']==1){
		$gid=$DB->query("select * from `pay_user` where `pid`='{$conf['zero_pid']}' limit 1")->fetch();
		if($gid===false)exit('{"code":-1,"msg":"注册收款商户ID不存在"}');
		$return_url = $siteurl.'User/Login.php?regok=1';
		$trade_no=date("YmdHis").rand(11111,99999);
		$out_trade_no=date("YmdHis").rand(111,999);
		
		$data = $type.'|'.$user.'|'.$pass.'|'.$email.'|'.$qq.'|'.$phone.'|'.$ip;
		$sds=$DB->exec("UPDATE `pay_regcode` SET `trade_no`='{$trade_no}',`data`='{$data}' WHERE `id`='{$row['id']}'");
		if($sds){
			$wxpay = array("id"=>1,"name"=>"wxpay","showname"=>"微信");
			$qqpay = array("id"=>2,"name"=>"qqpay","showname"=>"QQ钱包");
			$alipay = array("id"=>3,"name"=>"alipay","showname"=>"支付宝");
			$result=array("code"=>2,"msg"=>"订单创建成功！","trade_no"=>$trade_no,"need"=>$conf['reg_pay_price'],"paytype"=>array("1"=>$wxpay,"2"=>$qqpay,"3"=>$alipay));
		}else{
			exit('{"code":-1,"msg":"订单创建失败！'.$DB->errorCode().'"}');
		}
	}else{
		$pid='1'.mt_rand(10000000,99999999);
		$key = random(11);
		$money =$conf['reg_money']?$conf['reg_money']:'0.00';
		$sqs=$DB->exec("INSERT INTO `pay_user` (`user`,`pass`,`pid`,`key`,`qq`,`email`,`phone`,`money`,`addtime`) VALUES ('{$user}','{$pass}','{$pid}','{$key}','{$qq}','{$email}','{$phone}','{$money}','{$date}')");
		if($sqs){
			if(!empty($email)){
				$sub = $conf['sitename'].' - 注册成功通知';
				$msg = '<h2>商户注册成功通知</h2>感谢您注册'.$conf['web_name'].'！<br/>您的登录账号：'.$user.'<br/>您的登录密码：'.$pass.'<br/>您的商户ID：'.$pid.'<br/>您的商户key：'.$key.'<br/>'.$conf['web_name'].'官网：<a href="http://'.$_SERVER['HTTP_HOST'].'/" target="_blank">'.$_SERVER['HTTP_HOST'].'</a><br/>【<a href="'.$siteurl.'" target="_blank">商户管理后台</a>】';
				$result = send_mail($email, $sub, $msg);
			}
			$DB->exec("update `pay_regcode` set `status` ='1' where `id`='{$row['id']}'");
			$_SESSION['reg_submit']=time();
			exit('{"code":1,"msg":"申请商户成功！","pid":"'.$pid.'","key":"'.$key.'"}');
		}else{
			exit('{"code":-1,"msg":"申请商户失败！'.$DB->errorCode().'"}');
		}
	}
}elseif($act=='Login'){//登录商户
	$pid=daddslashes($_POST['pid']);
	$key=daddslashes($_POST['key']);
	$connect=daddslashes($_POST['connect']);
	$pidrow=$DB->query("SELECT * FROM pay_user WHERE pid='{$pid}' limit 1")->fetch();
		if(!$pid or !$key){
			$result=array("code"=>-1,"msg"=>"所有参数不能为空");
		}elseif($pid==$pidrow['pid']) {
			if($key==$pidrow['key'] or $key==$pidrow['pass']){
				$isrow=$DB->query("SELECT * FROM pay_user WHERE user='{$pidrow['user']}' limit 1")->fetch();
				if($isrow and $isrow['pass']){
					$pid = $isrow['user'];
					$key = $isrow['pass'];
				}else{
					$pid = $pidrow['pid'];
					$key = $pidrow['key'];
				}
				$session=md5($pid.$key.$password_hash);
				$expiretime=time()+604800;
				$token=authcode("{$pid}\t{$session}\t{$expiretime}", 'ENCODE', $conf['KEY']);
				setcookie("user_token", $token, time() + 604800);
				if($connect){
					unset($_SESSION['social_uid']);
					unset($_SESSION['access_token']);
					unset($_SESSION['nickname']);
					if(!$userrow['social_uid']){
						$DB->exec("update `pay_user` set `social_uid` ='{$_SESSION['social_uid']}',`nickname` ='{$_SESSION['nickname']}' where `pid`='{$pid}'");
						$result=array("code"=>1,"msg"=>"使用pid登录成功并绑定QQ快捷登陆成功");
					}else{
						$result=array("code"=>1,"msg"=>"使用pid登录成功但绑定QQ快捷登陆失败,原因：登陆商户已绑定QQ：".$userrow['nickname']);
					}
				}else{
					$result=array("code"=>1,"msg"=>"使用pid登录成功");
				}
				$city=get_ip_city($ip)['Result']['Country'];
				$DB->exec("insert into `pay_log` (`pid`,`type`,`date`,`ip`,`city`) values ('".$pidrow['pid']."','商户PID登录','".$date."','".$ip."','".$city."')");
			}else{
				$_SESSION['pass_error']++;
				$result=array("code"=>-1,"msg"=>"登录失败，KEY错误");
			}
		}else{
			$userrow=$DB->query("SELECT * FROM pay_user WHERE user='{$pid}' limit 1")->fetch();
			if($key==$userrow['key'] or $key==$userrow['pass']){
				$isrow=$DB->query("SELECT * FROM pay_user WHERE user='{$userrow['user']}' limit 1")->fetch();
				if($isrow and $isrow['pass']){
					$pid = $isrow['user'];
					$key = $isrow['pass'];
				}else{
					$pid = $userrow['pid'];
					$key = $userrow['key'];
				}
				$session=md5($pid.$key.$password_hash);
				$expiretime=time()+604800;
				$token=authcode("{$pid}\t{$session}\t{$expiretime}", 'ENCODE', $conf['KEY']);
				setcookie("user_token", $token, time() + 604800);
				if($connect){
					unset($_SESSION['social_uid']);
					unset($_SESSION['access_token']);
					unset($_SESSION['nickname']);
					if(!$userrow['social_uid']){
						$DB->exec("update `pay_user` set `social_uid` ='{$_SESSION['social_uid']}',`nickname` ='{$_SESSION['nickname']}' where `pid`='{$pid}'");
						$result=array("code"=>1,"msg"=>"使用账号登录成功并绑定QQ快捷登陆成功");
					}else{
						$result=array("code"=>1,"msg"=>"使用账号登录成功但绑定QQ快捷登陆失败,原因：登陆商户已绑定QQ：".$userrow['nickname']);
					}
				}else{
					$result=array("code"=>1,"msg"=>"使用账号登录成功");
				}
				$city=get_ip_city($ip)['Result']['Country'];
				$DB->exec("insert into `pay_log` (`pid`,`type`,`date`,`ip`,`city`) values ('".$userrow['pid']."','商户账号登录','".$date."','".$ip."','".$city."')");
			}else{
				$_SESSION['pass_error']++;
				$result=array("code"=>-1,"msg"=>"登录失败，密码错误");
			}
		}
}elseif($act=='Get_Ajax_Pone_code'){//提交手机验证码
	$qr_id = $_POST['qr_id']?$_POST['qr_id']:$_GET['qr_id'];
	$code = $_POST['code']?$_POST['code']:$_GET['code'];
	$result = $Pay_Cookie_Api->Get_Code($qr_id,$code);
	$result=array("code"=>$result['code'],"msg"=>$result['msg'],"id"=>$result['id'],"qr_url"=>$result['qr_url']);
}elseif($act=='Get_Login_QrCode'){//提交取登录二维码请求
	$type = $_POST['type']?$_POST['type']:$_GET['type'];
	$beizhu = $_POST['beizhu']?$_POST['beizhu']:$_GET['beizhu'];
	$qr_id = $_POST['qr_id']?$_POST['qr_id']:$_GET['qr_id'];
	$pay_user = $_POST['pay_user']?$_POST['pay_user']:$_GET['pay_user'];
	$pay_pass = $_POST['pay_pass']?$_POST['pay_pass']:$_GET['pay_pass'];
	if($userrow[$type.'_free_vip_time']<$date){
		$result=array("code"=>-1,"msg"=>"当前支付方式未开通会员,请前往首页开通免挂会员","id"=>0);
	}else{
		$result = $Pay_Cookie_Api->Get_Login_QrCode($type,$beizhu,$pay_user,$pay_pass);

		$DB->query("update `pay_qrlist` set `qr_id`='{$result['id']}',`pay_user`='{$pay_user}',`pay_pass`='{$pay_pass}',`data_data`='0' WHERE `id`='{$qr_id}' limit 1");
		$result=array("code"=>$result['code'],"msg"=>$result['msg'],"id"=>$result['id'],"qr_url"=>$result['qr_url']);
	}
}elseif($act=='Get_Wx_QrUrl'){//取登录二维码
	$id = $_POST['id']?$_POST['id']:$_GET['id'];
	$result = $Pay_Cookie_Api->Get_Wx_QrUrl($id);
	$result=array("code"=>$result['code'],"msg"=>$result['msg'],"id"=>$result['id'],"qr_url"=>$result['qr_url']);
}elseif($act=='Get_Login_Cookie'){//取登录cookie
	$id = $_POST['id']?$_POST['id']:$_GET['id'];
	$qr_id = $_POST['qr_id']?$_POST['qr_id']:$_GET['qr_id'];
	$result = $Pay_Cookie_Api->Get_Login_Cookie($id);
	// $isrow=$DB->query("SELECT * FROM pay_qrlist WHERE id='{$qr_id}' limit 1")->fetch();
	//  print_r($result);
	exit(json_encode($result));
	//这人tmd是不是傻逼这样写，老子弄了半天才发现
	// if($isrow['cookie'] and $isrow['cookie']!='Login_Out'){
	// 	$result=array("code"=>1,"msg"=>'登录成功',"id"=>$qr_id,"cookie"=>$isrow['cookie']);
	// }elseif($result['msg'] and $isrow['type']!='wxpay'){
	// }else{
	// 	$result=array("code"=>0,"msg"=>'等待登录中...',"id"=>$qr_id,"cookie"=>0);
	// }
}elseif($act=='Add_Qrcode'){//上传二维码
	$qr_nums = $DB->query("SELECT count(*) from pay_qrlist WHERE `pid`='{$userrow['pid']}'")->fetchColumn();
	$conf['qr_nums'] = $conf['qr_nums']?$conf['qr_nums']:10;
	if($qr_nums>=$conf['qr_nums']){
		$result=array("code"=>-1,"msg"=>"添加二维码失败,当前只允许每个商户上传".$conf['qr_nums']."张");
	}else{
		$rand=date("YmdHis").rand(11111,99999);
		$Add = copy($_FILES['image_field']['tmp_name'], ROOT.'User/QRCODE/'.$rand.'.png');
		// exit(1);
        if ($Add) {
            // $result = $Instant_Api->Add_Qrcode(ROOT.'User/QRCODE/'.$rand.'.png',$userrow['pid']);
            //我们在这里直接使用本地的码子，一直找不到合适的解码接口
        $result=array("code"=>1,"msg"=>"添加成功，请去登陆更新COOKIE吧","qrcode"=>urlencode('User/QRCODE/'.$rand.'.png'));
        }else{
            $result=array("code"=>-1,"msg"=>"上传失败");
        }
	}
}elseif($act=='Get_Qr'){	//取二维码数据
	$id=daddslashes($_POST['id'])?daddslashes($_POST['id']):daddslashes($_GET['id']);
	$is=$DB->query("SELECT * FROM `pay_qrlist` WHERE `pid`='{$userrow['pid']}' and `id`='{$id}' limit 1")->fetch();
		if(!$is){
			$result=array("code"=>-2,"msg"=>"非法操作");
		}else{
			$login_time = time();
			$rs=$DB->query("SELECT * FROM `pay_wechat_trumpet` WHERE `status`='1' and `login_time`>='{$login_time}' order by sort ASC");
			while($res = $rs->fetch())
			{
				$data[]=$res;
			}
			if(!$DB->query("SELECT * FROM `pay_wechat_trumpet` WHERE `status`='1' and `login_time`>='{$login_time}' limit 1")->fetch()){
				$data[]=array("wx_name"=>"暂无可添加微信","wx_user"=>"请联系站长");
			}
			$is['data_msg'] = $is['data_data'];
			$is['status_status'] = $is['status'];
			$result=array("code"=>1,"id"=>$is['id'],"type"=>$is['type'],"beizhu"=>$is['beizhu'],"data"=>$data,"qrdata"=>$is);
		}
}elseif($act=='Add_Qr'){	//添加二维码
	$type=$_POST['type'];
	$qr_url=$_POST['qr_url'];
	$cookie=$_POST['cookie'];
	$beizhu=$_POST['beizhu'];
	//$cookie=base64_decode($cookie);
	/*
	$Pay_Money= $Pay_Money_Api->Get_pay_money($type,$cookie);
	if(!$Pay_Money['status']){
		$result=array("code"=>-3,"msg"=>"添加失败,登录COOKIE不正确,请刷新页面重试!");
	}else*/if($userrow[$type.'_free_vip_time']<$date){
		$result=array("code"=>-1,"msg"=>"当前支付方式未开通会员,请前进左栏商户管理按钮进行开通免挂会员");
	}elseif(!$qr_url){
		$result=array("code"=>-2,"msg"=>"确保所有项不能为空");
	}elseif($qr_url=='解码中'){
			$result=array("code"=>-1,"msg"=>"上传失败，请先等二维码解码成功再点击上传");
	}else{
		$sqs=$DB->exec("INSERT INTO `pay_qrlist` (`pid`,`type`, `qr_url`,`cookie`,`money`,`beizhu`,`status`,`addtime`) VALUES ('{$userrow['pid']}','{$type}','{$qr_url}','0','0.00','{$beizhu}','0','{$date}')");
		if($sqs){
			Add_log($userrow['pid'],'添加二维码');
			$result=array("code"=>1,"msg"=>"添加二维码成功");
		}else{
			$result=array("code"=>-1,"msg"=>"添加二维码失败".'['.$DB->errorInfo()[1].'] '.$DB->errorInfo()[2]);
		}
	}
}elseif($act=='Up_Qr'){//更新二维码
	$id=daddslashes($_POST['id'])?daddslashes($_POST['id']):daddslashes($_GET['id']);
	$cookie=daddslashes($_POST['cookie'])?daddslashes($_POST['cookie']):daddslashes($_GET['cookie']);
	//$cookie=base64_decode($cookie);
	$is=$DB->query("SELECT * FROM `pay_qrlist` WHERE `pid`='{$userrow['pid']}' and `id`='{$id}' limit 1")->fetch();
	$Pay_Money= $Pay_Money_Api->Get_pay_money($is['type'],$cookie);
	if(!$Pay_Money['status'] and $is['type']!='wxpay'){
		if($is['type']=='alipay'){
			$type_name = '支付宝';
		}elseif($is['type']=='qqpay'){
			$type_name = 'QQ';
		}else{
			$type_name = '微信';
		}
	//	$result=array("code"=>-3,"msg"=>"更新失败,请刷新页面重试,请换个! ".$type_name." 账号重试");
	}elseif(!$is){
			$result=array("code"=>-2,"msg"=>"非法操作");
		}else{
			$sqs=$DB->exec("update `pay_qrlist` set `cookie`='{$cookie}',`money`='{$Pay_Money['money']}',`status`='1',`addtime`='{$date}' where id='{$id}'");
			if($sqs){
				Add_log($userrow['pid'],'更新二维码');
		
				$result=array("code"=>1,"msg"=>"更新成功");
			}else{
	//			$result=array("code"=>-1,"msg"=>"更新失败".'['.$DB->errorInfo()[1].'] '.$DB->errorInfo()[2]);
			}
		}
}elseif($act=='Del_Qr'){//删除二维码
	$id=daddslashes($_POST['id']);
	$is=$DB->query("SELECT * FROM `pay_qrlist` WHERE `pid`='{$userrow['pid']}' and `id`='{$id}' limit 1")->fetch();
		if(!$is){
			$result=array("code"=>-2,"msg"=>"非法操作");
		}else{
			$sql="DELETE FROM `pay_qrlist` WHERE `id`='{$id}' limit 1";
			if($DB->exec($sql)){
				Add_log($userrow['pid'],'删除二维码');
				$result=array("code"=>1,"msg"=>"删除成功");
			}else{
				$result=array("code"=>-1,"msg"=>"删除失败");
			}
		}
}elseif($act=='Qrlist'){	//二维码详细
	$id=trim($_GET['id']);
	$row=$DB->query("select * from pay_qrlist where id='$id' limit 1")->fetch();
	if(!$row)
		exit('{"code":-1,"msg":"当前订单不存在或未成功选择支付通道！"}');
  $today=date("Y-m-d").' 00:00:00';
  $today2=date("Y-m-d").' 23:59:59';
  $lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
  $lastday2=date("Y-m-d",strtotime("-1 day")).' 23:59:59';
  $qr_id=$row['id'];
	 // //商户总订单数量
  $ddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}'")->fetchColumn();
  //商户总成功订单数量
  $zcgddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}' and status='1'")->fetchColumn();
  //商户总未完成订单数量
  $zwwcddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}' and status!='1'")->fetchColumn();
  //商户总跑分订单金额
  $zpfddje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}'")->fetchColumn();
  //商户总跑分成功金额
  $zpfcgje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}' and status='1'")->fetchColumn();
  //商户总跑分未完成金额
  $zpfwwcje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}' and status!='1'")->fetchColumn();
  //今日商户总订单数量
  $jrzddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //今日商户总成功订单数量
  $jrzcgddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}' and status='1' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //今日商户总未完成订单数量
  $jrzwwcddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}' and status!='1' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //今日商户总跑分订单金额
  $jrzpfddje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //今日商户总跑分成功金额
  $jrzpfcgje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}' and status='1' and endtime>='$today' and endtime<='$today2'")->fetchColumn();
  //今日商户总跑分未完成金额
  $jrzpfwwcje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}' and status!='1' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //昨日商户总订单数量
  $zrzddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}' and addtime>='$lastday' and addtime<='$lastday2'")->fetchColumn();
  //昨日商户总成功订单数量
  $zrzcgddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}' and status='1' and addtime>='$lastday' and addtime<='$lastday2'")->fetchColumn();
  //昨日商户总未完成订单数量
  $zrzwwcddsl=$DB->query("SELECT count(*) from pay_order where qr_id='{$qr_id}' and status!='1' and addtime>='$lastday' and addtime<='$lastday2'")->fetchColumn();
  //昨日商户总跑分订单金额
  $zrzpfddje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}' and addtime>='$lastday' and addtime<='$lastday2'")->fetchColumn();
  //昨日商户总跑分成功金额
  $zrzpfcgje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}' and status='1' and endtime>='$lastday' and endtime<='$lastday2'")->fetchColumn();
  //昨日商户总跑分未完成金额
  $zrzpfwwcje=$DB->query("SELECT sum(money) from pay_order where qr_id='{$qr_id}' and status!='1' and addtime>='$lastday' and addtime<='$lastday2'")->fetchColumn();
  
  $row['ali_order'] = '总订单：'.$ddsl.'<br>'.'已完成：'.$zcgddsl.'<br>'.'未完成：'.$zwwcddsl.'<br>'.'成功率：'.(round((($zcgddsl?$zcgddsl:1) / ($ddsl?$ddsl:1)),2)*100).'%</td>
	<td>'.'总金额：'.$zpfddje.'<br>'.'已完成：'.$zpfcgje.'<br>'.'未完成：'.$zpfwwcje;
	
  $row['jr_order'] = '总订单：'.$jrzddsl.'<br>'.'已完成：'.$jrzcgddsl.'<br>'.'未完成：'.$jrzwwcddsl.'<br>'.'成功率：'.(round((($jrzcgddsl?$jrzcgddsl:1) / ($jrzddsl?$jrzddsl:1)),2)*100).'%</td>
	<td>'.'总金额：'.$jrzpfddje.'<br>'.'已完成：'.$jrzpfcgje.'<br>'.'未完成：'.$jrzpfwwcje;
	
  $row['zr_order'] = '总订单：'.$zrzddsl.'<br>'.'已完成：'.$zrzcgddsl.'<br>'.'未完成：'.$zrzwwcddsl.'<br>'.'成功率：'.(round((($zrzcgddsl?$zrzcgddsl:1) / ($zrzddsl?$zrzddsl:1)),2)*100).'%</td>
	<td>'.'总金额：'.$zrzpfddje.'<br>'.'已完成：'.$zrzpfcgje.'<br>'.'未完成：'.$zrzpfwwcje;
  $row['type'] = '<img src="/Core/Assets/Icon/'.$row['type'].'.ico" width="16">'.pay_type($row['type']);
	
	$result=array("code"=>0,"msg"=>"succ","data"=>$row);
}elseif($act=='Instant_Notify'){//补单并回调
	$trade_no=daddslashes($_POST['trade_no']);
	$srow=$DB->query("SELECT * FROM pay_order WHERE `pid`='{$userrow['pid']}' and trade_no='{$trade_no}' limit 1")->fetch();
	$url=creat_callback($srow);
	$data=get_curl($url['notify']);
	Add_log($userrow['pid'],'人工补单回调：'.$trade_no);
	$result=array("code"=>1,"msg"=>"补单成功,并成功回调,接口返回数据：".$data);
}elseif($act=='Get_Work'){//工单系统
	$types=daddslashes(strip_tags($_POST['types']));
	$biaoti=daddslashes(strip_tags($_POST['biaoti']));
	$text=daddslashes(strip_tags($_POST['text']));
	$qq=daddslashes(strip_tags($_POST['qq']));
	if($conf['work_zt']==0)exit('{"code":-1,"msg":"未开放工单系统"}');
	if(isset($_SESSION['work_submit']) && $_SESSION['work_submit']>time()-2400){
		exit('{"code":-1,"msg":"请勿频繁提交工单"}');
	}
	$num = rand(100000000,999999999);
	$edata=date("Y-m-d");
	$sds=$DB->query("INSERT INTO `pay_work` (`uid`, `num`, `types`, `biaoti`, `text`, `qq`, `edata`, `wdata`, `active`) VALUES ('{$userrow['pid']}', '{$num}', '{$types}', '{$biaoti}', '{$text}', '{$qq}', '{$edata}', ' ', '0')");
	if($sds){
		$_SESSION['work_submit']=time();
		$email=$conf['web_mail'];
		$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/';
		$sub = $conf['sitename'].' - 您有新的工单要处理';
		$msg = '<h2>新的工单等你来处理</h2>尊敬的管理员您的网站【'.$conf['sitename'].'】收到一个工单<br/>工单编号为：'.$num.'<br/>工单类型为：'.$types.'<br/>工单标题为：'.$biaoti.'<br/>请尽快登录后台处理订单：【<a href="'.$siteurl.'" target="_blank">登录后台</a>】<br/>';
		$result = send_mail($email, $sub, $msg);
		Add_log($userrow['pid'],'提交工单');
			exit('{"code":1,"msg":"succ"}');
			$result=array("code"=>1,"msg"=>"succ");
		}else{
			$result=array("code"=>-1,"msg"=>"提交失败".'['.$DB->errorInfo()[1].'] '.$DB->errorInfo()[2]);
		}
}elseif($act=='Set'){//修改商户设置
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	$qq=daddslashes($_POST['qq']);
	$is=$DB->query("SELECT * FROM `pay_user` WHERE `pid`='{$userrow['pid']}'limit 1")->fetch();
		if(!$is){
			$result=array("code"=>-2,"msg"=>"非法操作");
		}elseif($user!=$is['user']){
			$i_s=$DB->query("SELECT * FROM `pay_user` WHERE `user`='{$user}'limit 1")->fetch();
			if(strlen($user)<6 or strlen($pass)<6){
				$result=array("code"=>-1,"msg"=>"修改失败,登录账号或密码不能低于6位数");
			}elseif(!$i_s){
				$sqs=$DB->exec("update `pay_user` set `user`='{$user}',`pass`='{$pass}',`qq`='{$qq}' where pid='{$userrow['pid']}'");
				if($sqs){
					Add_log($userrow['pid'],'修改商户信息');
					$result=array("code"=>1,"msg"=>"修改成功");
				}else{
					$result=array("code"=>-1,"msg"=>"修改失败".'['.$DB->errorInfo()[1].'] '.$DB->errorInfo()[2]);
				}
			}else{
				$result=array("code"=>1,"msg"=>"修改失败,此用户名已存在,请更换其他用户名");
			}
		}else{
			$sqs=$DB->exec("update `pay_user` set `pass`='{$pass}',`qq`='{$qq}' where pid='{$userrow['pid']}'");
			if($sqs){
				$result=array("code"=>1,"msg"=>"修改成功");
			}else{
				$result=array("code"=>-1,"msg"=>"修改失败".'['.$DB->errorInfo()[1].'] '.$DB->errorInfo()[2]);
			}
		}
}elseif($act=='Pay_set'){//支付设置
	$outtime=daddslashes($_POST['outtime']);
	$alipay_pay_open=daddslashes($_POST['alipay_pay_open']);
	$alipay_api_url=daddslashes($_POST['alipay_api_url']);
	$alipay_api_pid=daddslashes($_POST['alipay_api_pid']);
	$alipay_api_key=daddslashes($_POST['alipay_api_key']);
	$qqpay_pay_open=daddslashes($_POST['qqpay_pay_open']);
	$qqpay_api_url=daddslashes($_POST['qqpay_api_url']);
	$qqpay_api_pid=daddslashes($_POST['qqpay_api_pid']);
	$qqpay_api_key=daddslashes($_POST['qqpay_api_key']);
	$is=$DB->query("SELECT * FROM `pay_user` WHERE `pid`='{$userrow['pid']}'limit 1")->fetch();
		if(!$is){
			$result=array("code"=>-2,"msg"=>"非法操作");
		}elseif($outtime>500){
			$result=array("code"=>-2,"msg"=>"修改失败,支付超时时间最大不能超过500秒");
		}else{
			$sqs=$DB->exec("update `pay_user` set `outtime`='{$outtime}',`alipay_pay_open`='{$alipay_pay_open}',`alipay_api_url`='{$alipay_api_url}',`alipay_api_pid`='{$alipay_api_pid}',`alipay_api_key`='{$alipay_api_key}',`qqpay_pay_open`='{$qqpay_pay_open}',`qqpay_api_url`='{$qqpay_api_url}',`qqpay_api_pid`='{$qqpay_api_pid}',`qqpay_api_key`='{$qqpay_api_key}' where pid='{$userrow['pid']}'");
			if($sqs){
				Add_log($userrow['pid'],'修改商户支付配置信息');
				$result=array("code"=>1,"msg"=>"修改成功");
			}else{
				$result=array("code"=>-1,"msg"=>"修改失败".'['.$DB->errorInfo()[1].'] '.$DB->errorInfo()[2]);
			}
		}
}elseif($act=='Reset_key'){//重置key密钥
	if(isset($_SESSION['Reset_Token']) && $_SESSION['Reset_Token']>time()-3600){
		exit('{"code":-1,"msg":"请勿频繁修改秘钥"}');
	}
	$key = random(11);
	Add_log($userrow['pid'],'重置key密钥');
	$DB->exec("update `pay_user` set `key` ='{$key}' where `pid`='{$userrow['pid']}'");
	$_SESSION['Reset_Token']=time();
	exit('{"code":1,"msg":"完成"}');
}elseif($act=='Change_pay_Template'){//修改支付页模板
	Add_log($userrow['pid'],'修改支付页面');
	$pay_template=daddslashes($_POST['pay_template']);
	$DB->exec("update `pay_user` set `pay_template` ='{$pay_template}' where `pid`='{$userrow['pid']}'");
	$_SESSION['Reset_Token']=time();
	exit('{"code":1,"msg":"succ"}');
}elseif($act=='QQloginqrcode'){//QQ跳转
	$image=trim($_POST['image']);
	$result = qrcodelogin($image);
}elseif($act=='Pay_Buy'){//自助开通会员
	$type=daddslashes($_POST['type']);
	$money = $conf[$type.'_free_vip_money'];
	if($type == 'alipay'){
		$type_name = '支付宝';
		$name = '开/续一月支付宝免挂会员';
	}elseif($type == 'qqpay'){
		$type_name = 'QQ钱包';
		$name = '开/续一月QQ钱包免挂会员';
	}else{
		$type_name = '微信';
		$name = '开/续一月微信免挂会员';
	}
	$trade_no=date("YmdHis").rand(11111,99999);	
	$result=array("code"=>1,"msg"=>"创建支付订单成功","trade_no"=>$trade_no,"money"=>$money,"type_name"=>$type_name,"name"=>$name);
}else{
	$result=array("code"=>-9,"msg"=>"参数错误");
}
if($result)
	exit(json_encode($result));
else
	exit($data);
?>