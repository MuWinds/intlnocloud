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
//检测金额回调	
function check_money_notify($row,$send = null){
		global $Pay_Money_Api,$Instant_Api,$DB,$conf,$date,$Pay_Cookie_Api;
		if($row['type']=='qqpay'){
			$timess=time()+rand(20,45);//当前时间戳 +30秒(下次执行时间)
		}else{
			$timess=time()+rand(10,25);//当前时间戳 +30秒(下次执行时间)
		}
		$money = $Pay_Money_Api->Get_pay_money($row['type'],$row['cookie']);
		if($money['money']>=0.01)$Pay_Money = $money['money'];else$Pay_Money = '0.00';
		$money_ = $row['money'];
		if($money_!=$Pay_Money){
			$Qr_Money = $Pay_Money-$money_;//实时余额减去上次系统记录余额=本次收款到账金额
		}
		
		if($money['status']!=1 and $money['money'] == "Cookie Time-out" and $Pay_Money_Api->Get_pay_money($row['type'],$row['cookie'])['money'] == "Cookie Time-out"){//如果监控不到金额,则COOKIE失效
			$DB->exec("update `pay_qrlist` set `status`='0',`cookie`='0',`data_data`='0',`endtime`='{$date}' WHERE `id`='{$row['id']}'");//更新失效数据
			//判断是否是支付宝掉线 并且存在登陆账号密码
			if($row['type'] == 'alipay' and $row['pay_user'] and $row['pay_pass']){
				$result = $Pay_Cookie_Api->Get_Login_QrCode($row['type'],$row['beizhu'],$row['pay_user'],$row['pay_pass']);//提交自动更新支付宝CK
				if($result['id']){
					$DB->exec("update `pay_qrlist` set `status`='2',`cookie`='0',`qr_id`='{$result['id']}',`data_data`='已经提交给云端,等待登陆哦',`endtime`='{$date}' WHERE `id`='{$row['id']}'");//更新数据
				}else{
					$DB->exec("update `pay_qrlist` set `status`='3',`cookie`='0',`data_data`='自动更新失败,可能站长没开启自动更新插件',`endtime`='{$date}' WHERE `id`='{$row['id']}'");//更新数据
				}
			}
			$rew=$DB->query("SELECT * FROM pay_user WHERE pid='{$row['pid']}' limit 1")->fetch();
			if($row['type']=='alipay'){
				$E_type = '支付宝';
			}elseif($row['type']=='qqpay'){
				$E_type = 'QQ钱包';
			}else{
				$E_type = '微信';
			}
			$email=$rew['qq'].'@qq.com';
			$sub = $conf['sitename'].' - COOKIE失效提醒';
			$msg = '尊敬的：'.$conf['sitename'].'用户,您好! 您在'.$conf['sitename'].'上挂的['.$E_type.']COOKIE失效了,为了不影响您继续使用,请务必去更新,地址:http://'.$_SERVER['HTTP_HOST'];
			if($send==true){$send_res = send_mail($email, $sub, $msg);}
		}elseif($Qr_Money<=0 or $Pay_Money==0.00){
			$DB->exec("update `pay_qrlist` set `money`='{$Pay_Money}',`crontime`='{$timess}' WHERE `id`='{$row['id']}'");//更新数据
		}
		
		if($Qr_Money>0){//判断是否是收入金额
			$api_type = $row['pid'];
			$api_type  = daddslashes(substr(md5(urlencode($_SERVER['HTTP_HOST'].'_'.$api_type)),8,10)).'_'.$row['type'];
			$Get_Money_Notify = $Instant_Api->Get_Money_Notify($api_type,$Qr_Money);//提交给云端并等待云端做出回调响应
			if($Get_Money_Notify['code']==1){
				$pay_msg = '提交给云端成功';
			}else{
				$pay_msg = '提交给云端失败';
			}
			$DB->query("insert into `pay_notify` (`pid`,`qr_id`,`qr_beizhu`,`api_type`,`money`,`pay_msg`,`status`,`nums`,`crontime`,`addtime`) values ('".$row['pid']."','".$row['id']."','".$row['beizhu']."','".$api_type."','".$Qr_Money."','".$pay_msg."','0','0','0','".$date."')");
			$DB->exec("update `pay_qrlist` set `money`='{$Pay_Money}',`crontime`='{$timess}' WHERE `id`='{$row['id']}'");//更新数据
		}
		return $row['id'].'----'.$Pay_Money.'----'.$Get_Money_Notify['msg'].$api_type.'<br>';
}
/**
     * 判断最近的API服务器接口/获取连接耗时
     *
     * @param array/string $data_URl    如果是域名组数，那么就会自动过滤组数中异常的服务器并选择从本站连接最快的地址,如果是单个域名,那么返回连接耗时
     * @return string
	 *
	 *
	 *组数例如（返回：从本站连接最快地址【http://dns.99ypay.cn/】）:	
	 *		$Instant_url_list = array(
	 *			'http://ds.99ypay.cn/',
	 *			'http://dns.99ypay.cn/',
	 *			'http://qinjireno.gz01.bdysite.com/',
	 *			'http://www.qq1111mzz.cn/'
	 *		);
	 *单个域名输入(返回:连接耗时【152.345】)
	 *		 	http_runtime('http://dns.99ypay.cn/');
     */
function http_runtime($data_URl)//@data_URl
{
	for($i=0; $i<3; $i++)
	{
		list($usec, $sec) = explode(' ', microtime());  
        $t1 = ((float)$usec + (float)$sec); 
		$ch=curl_init($data_URl.'Cron_Api.php');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 4.4.1; zh-cn; R815T Build/JOP40D) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$return=curl_exec($ch);
		$httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		list($usec, $sec) = explode(' ', microtime());  
        $t2 = ((float)$usec + (float)$sec); 
		$num= $t2-$t1;
		$mis+=$num;
	}
		if(is_array($data_URl))
		{
			$Apis_ms = 999;
			foreach ($data_URl as $key => $val) {
			$ms_ = http_runtime($data_URl[$key]);
			preg_match('#<font color=red>(.*?)</font>#',$ms_,$z_t);
			preg_match('#<font color=green>(.*?)</font>#',$ms_,$m_s);
			$ms = $m_s[1];
				if($Apis_ms>$ms and $z_t[1]!='异常'){//将第一个默认为最小的值和数组中的所有值比较，如果默认的最小值比其他的值大，那叫交换，最终遍历完后，$min中存储的就是数组中的最小的值
					$Apis_ms = $ms;
					$Apis_url = $data_URl[$key];
				}
			}
			return $Apis_url.$Apis_ms; //得到连接耗时
		}else{
			if($httpcode==200)
				if(round(($mis * 1000)/3,3)<800)//如果大于800则可能影响速度,所以颜色需要改变
					return '<font color=green>'.round(($mis * 1000)/3,3).'</font>'; //得到连接耗时
				else
					return '<font color=red>'.round(($mis * 1000)/3,3).'</font>'; //得到连接耗时
			else
				return '<font color=red>异常</font>'; //得到连接耗时
		}
}
//添加操作记录
function Add_log($pid = 'admin',$type) {
	global $DB,$ip,$date;
	$city=get_ip_city($ip)['Result']['Country'];
	$DB->exec("insert into `pay_log` (`pid`,`type`,`date`,`ip`,`city`) values ('".$pid."','".$type."','".$date."','".$ip."','".$city."')");
	return '添加成功'; 
}
function checkServerSatatus($ip) {
	$str = null;
	$ip = str_replace("http://","",$ip);
	$ip = str_replace("/","",$ip);
	list($usec, $sec) = explode(' ', microtime());  
    $t1 = ((float)$usec + (float)$sec); 
	$fp = @fsockopen($ip,80,$errno,$errstr,10);
	if (!$fp) {
		return array('code'=>0,'msg'=>'不在线[异常]');
	} else {
		fclose($fp);
		list($usec, $sec) = explode(' ', microtime());  
        $t2 = ((float)$usec + (float)$sec); 
		$num= $t2-$t1;
		$mis+=$num;
		$mis = round((round(($mis * 1000)/4,3) * 10)/2,3);
		$get_ip_city = get_ip_city($ip)['Result'];
			if($mis<100)//如果大于800则可能影响速度,所以颜色需要改变
				$Ping = '<font color=green>'.$mis.'</font>'; //得到连接耗时
			elseif($mis<150)//如果大于800则可能影响速度,所以颜色需要改变
				$Ping = '<font color=#ff3399>'.$mis.'</font>'; //得到连接耗时
			else
				$Ping = '<font color=red>'.$mis.'</font>'; //得到连接耗时
		return array('code'=>1,'msg'=>'在线[正常]','IP'=>$get_ip_city['IP'],'Country'=>$get_ip_city['Country'],'Ping'=>$Ping);
	}
}
function submit_pay($parameter,$method = 'POST',$key ,$HTTP_HOST = null){//发起支付支付
	$HTTP_HOST = $HTTP_HOST?$HTTP_HOST.'submit.php':($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/submit.php';
	$sign_type = 'MD5';
	$sign = MD5("money=".$parameter['money']."&name=".$parameter['name']."&notify_url=".$parameter['notify_url']."&out_trade_no=".$parameter['out_trade_no']."&pid=".$parameter['pid']."&return_url=".$parameter['return_url']."&sitename=".$parameter['sitename']."&type=".$parameter['type'].$key);
	return '<body onLoad="document.uncome.submit()">
	<form name="uncome" action="'.$HTTP_HOST.'" method="'.$method.'">
	<input type="hidden" name="pid"  value="'.$parameter['pid'].'">
	<input type="hidden" name="type"  value="'.$parameter['type'].'">
	<input type="hidden" name="out_trade_no"  value="'.$parameter['out_trade_no'].'">
	<input type="hidden" name="notify_url"  value="'.$parameter['notify_url'].'">
	<input type="hidden" name="return_url"  value="'.$parameter['return_url'].'">
	<input type="hidden" name="name"  value="'.$parameter['name'].'">
	<input type="hidden" name="money"  value="'.$parameter['money'].'">
	<input type="hidden" name="sitename"  value="'.$parameter['sitename'].'">
	<input type="hidden" name="sign"  value="'.$sign.'">
	<input type="hidden" name="sign_type"  value="'.$sign_type.'">';
}
function submit_sign($data,$sign,$key){//发起支付的签名验证
	$mysgin_a = MD5("money=".$data['money']."&name=".$data['name']."&notify_url=".$data['notify_url']."&out_trade_no=".$data['out_trade_no']."&pid=".$data['pid']."&return_url=".$data['return_url']."&sitename=".$data['sitename']."&type=".$data['type'].$key);
	$mysgin_b = MD5("money=".$data['money']."&name=".$data['name']."&notify_url=".$data['notify_url']."&out_trade_no=".$data['out_trade_no']."&pid=".$data['pid']."&return_url=".$data['return_url']."&type=".$data['type'].$key);
	if($mysgin_a == $sign or $mysgin_b == $sign) {
		return true;
	}
	else {
		return false;
		//return $mysgin;
	}
}
function verifyNotify($key){//发起支付的签名验证
	if(empty($_GET)) {//判断POST来的数组是否为空
			return false;
	}
	$mysgin  = MD5("money=".$_GET['money']."&name=".$_GET['name']."&out_trade_no=".$_GET['out_trade_no']."&pid=".$_GET['pid']."&trade_no=".$_GET['trade_no']."&trade_status=TRADE_SUCCESS&type=".$_GET['type'].$key);
	if($mysgin == $_GET['sign']) {
		return true;
	}
	else {
		return false;
		//return $mysgin;
	}
}

function creat_callback($data){//异步回调
	global $DB, $conf, $date;
	$userrow=$DB->query("SELECT * FROM pay_user WHERE pid='{$data['pid']}' limit 1")->fetch();
	$sign = MD5("money=".$data['money']."&name=".$data['name']."&out_trade_no=".$data['out_trade_no']."&pid=".$data['pid']."&trade_no=".$data['trade_no']."&trade_status=TRADE_SUCCESS&type=".$data['type'].$userrow['key']);
	$array=array('pid'=>$data['pid'],'trade_no'=>$data['trade_no'],'out_trade_no'=>$data['out_trade_no'],'type'=>$data['type'],'name'=>$data['name'],'money'=>$data['money'],'trade_status'=>'TRADE_SUCCESS');
	$urlstr=http_build_query($array);
	if($data['status']==0)$DB->query("update `pay_user` set `money`=`money`-'{$data['money']}' where pid='{$data['pid']}'");//扣去额度
	
	$DB->query("update `pay_order` set `status` ='1',`endtime` ='{$date}' where `trade_no`='{$data['trade_no']}'");
	
	if(strpos($data['notify_url'],'?'))
		$url['notify']=$data['notify_url'].'&'.$urlstr.'&sign='.$sign.'&sign_type=MD5';
	else
		$url['notify']=$data['notify_url'].'?'.$urlstr.'&sign='.$sign.'&sign_type=MD5';
	if(strpos($data['return_url'],'?'))
		$url['return']=$data['return_url'].'&'.$urlstr.'&sign='.$sign.'&sign_type=MD5';
	else
		$url['return']=$data['return_url'].'?'.$urlstr.'&sign='.$sign.'&sign_type=MD5';
	return $url;
}

function callback_sign($data,$name){ //获取签名算法  接口需要发送提交云端
	global $DB, $conf;
	$sign = MD5("money=".$data['money']."&name=".$name."&out_trade_no=".$data['out_trade_no']."&pid=10008888888888&trade_no=".$data['trade_no']."&trade_status=TRADE_SUCCESS&type=".$data['api_type'].$conf['Instant_key']);
	return $sign;
}

function trimall($str,$pid)//删除空格,替换不可提交到云端的符号
{
	global $conf;
	$qian=array(" ","	","\t","\n","\r","#","[","]","【","】","-","0","1","2","3","4","5","6","7","8","9");
	$hou=array("","","","","","");
    return mb_substr(MD5(str_replace($qian,$hou,$str.$conf['sitename'])),0,15,'utf-8').$pid;    
}

function qrcode($qrcode = 'https://ww1.sinaimg.cn/large/005BYqpggy1fxjot06nomj309l09cwez.jpg')//二维码解码
{
	global $conf;
	$post['data'] = $qrcode;
	$result=get_curl('https://cli.im/Api/Browser/deqr',$post);
	$json = json_decode($result, true);
	if($json['status']!=1)
		return false;
	else
		return $json['data']['RawData'];
}

function short_url($url){//短网址生成
	$short_url_api = 'http://api.t.sina.com.cn/short_url/shorten.json?source=3271760578&url_long='.$url;
	$short_url_api = 'https://v1.alapi.cn/api/url?url='.$url.'&type=1&format=json';
	$data = get_curl($short_url_api);
	//$explode = explode('[',$data);
	//$explode = explode(']',$explode[1]);
	//$json = json_decode($explode[0], true);
	$json = json_decode($data, true);
	return $json['data']['short_url'];
}

function getQQNick($qq){//获取QQ网名
   $get_info = file_get_contents('http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?get_nick=1&uins='.$qq);
    //转换编码
    $get_info = mb_convert_encoding($get_info, "UTF-8", "GBK");
    //对获取的json数据进行截取并解析成数组
    $name = json_decode(substr($get_info,17,-1),true);
	return $name[$qq][6];
}
function PinYin($msg){//汉字转拼音
//申请地址
   $get_info = file_get_contents('http://api.k780.com:88/?app=code.hanzi_pinyin&typeid=1&wd='.$msg.'&appkey=35181&sign=43d272907c471692161991e2e8a54eb0&format=json');
    // {"success":"1","result":{"typeid":"1","wd":"零度爸爸","ret":"qin ji ren "}}
    //对获取的json数据进行截取并解析成数组
    $name = json_decode($get_info,true);
	return preg_replace('# #','',$name['result']['ret']);
}

function wachat_login_zt($login_time)//微信店员在线状态
{
	if($login_time>=time())
		return '<font color=green>在线</font>';
	else
		return '<font color=red>不在线</font>';
}
function wachat_zt($status)//上下架状态
{
	if($status==1)
		return '<font color=green>已上架</font>';
	else
		return '<font color=red>已下架</font>';
}

function order_zt($status)//订单状态
{
	if($status==1)
		return '<font color=green>已完成</font>';
	elseif($status==2)
		return '<font color=red>订单失效</font>';
	else
		return '<font color=red>未完成</font>';
}
function pay_type($type)//支付方式中文化
{
	if(strstr($type, 'qqpay'))
		return '<font color=green>QQ钱包</font>';
	elseif(strstr($type, 'wxpay'))
		return '<font color=green>微信</font>';
	elseif(strstr($type, 'alipay'))
		return '<font color=green>支付宝</font>';
}
function cookie_zt($zt,$type,$addtime,$endtime)//cookie检测
{
	if($zt!=1)
		if($type=='wxpay')
			return '<font color=red>微信未绑定店员或已解绑->'.$endtime.'</font>';
		else
			return '<font color=red>CK状态失效->'.$endtime.'</font>';
	else
		if($type=='wxpay')
			return '<font color=green>微信已成功绑定</font>';
		else
			return '<font color=green>CK状态正常</font>';
}

function price_zt($price,$apittime)//出码状态
{
	if($price==-0.01)
		return '<font color=red>站点云端额度不足,请提醒站长进行充值</font>';
	elseif($price>0.00)
		return '<font color=green>与云端通讯完成</font>';
	elseif($res['apittime']>=time())
		return '<font color=red>正在与云端通讯...</font>';
	else
		return '<font color=red>与云端通讯失败,当前云端服务器可能故障</font>';
}
?>