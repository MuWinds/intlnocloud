<?php

/**
 * 这个文件是我重新写过的，他原来是在云端进行监控的，但是我们脱离了云端只能本地运行！这里我用了多线程写的速度也跟得上
 * 他写的代码我已经注释掉了，没有删，想学习的话可以看
 * Author ：wlkjyy
 */
if (preg_match('/Baiduspider/', $_SERVER['HTTP_USER_AGENT'])) exit;
$nosession = true;
require './Core/Common.php';
require __DIR__ . '/Thread.php'; //载入多线程
// $limit=10;//每次执行条数
// $time=time();//当前时间戳


//获取所有运行中的收款码

$rs = $DB->query("SELECT * from pay_qrlist WHERE status='1' and type!='wxpay'");
while ($row = $rs->fetch()) {

	// echo $row['id'];
	//这里我们要为每一个收款码创建一个缓存
	$pass_file = './cache/' . md5($row['id']) . '.tmp';
	if (is_dir('./cache') == false) mkdir('./cache');
	if (is_file($pass_file) == false) file_put_contents($pass_file, '');

	//QQ需要取出UIN
	if ($row['type'] == 'qqpay') {
		preg_match('/uin\=o(.*?)\;/', base64_decode($row['cookie']), $uin);
		$uin = $uin[1];
	}

	//把数据全部加入进线程
	$thread_data[] = [
		'id' => $row['id'],
		'type' => $row['type'],
		'cookie' => base64_decode($row['cookie']),
		'url' => ($row['type'] == 'qqpay' ? 'https://myun.tenpay.com/cgi-bin/clientv1.0/qwallet_record_list.cgi?limit=15&offset=0&s_time=2019-04-20&ref_param=&source_type=7&time_type=0&bill_type=3&uin=' . $uin : 'https://mrchportalweb.alipay.com/user/asset/queryData?_ksTS=1564846095488_41&_input_charset=utf-8&ctoken=FQYMdJvdmydgpjeM'),
	];
}

//我们将接口加入线程
$thread = curl2($thread_data);
//整个系统没有一个通道，直接结束，不然耗资源
if ($thread == false) exit('Crontab Success!');
$thread_count = count($thread);
//发送数据总数
$thread_data_count = count($thread_data);
if ($thread_count != $thread_data_count) exit('{"code":-1,"message":"数据上报异常"}');
for ($i = 0; $i < $thread_data_count; $i++) {
	$id = $thread_data[$i]['id'];
	//通道类型
	$type = $thread_data[$i]['type'];
	//查询出通道数据
	$row = $DB->query('select * from `pay_qrlist` where `id` = "' . $id . '";')->fetch();
	// echo $thread[$i];
	//我们开始判断支付方式，必须过滤掉wxpay微信支付
	if ($type != 'wxpay') {
		if ($type == 'alipay') {
			preg_match_all('/{\"totalAvailableBalance\":\"(.*?)\",/is', $thread[$i], $trStr);
			$money = str_replace(',', '', $trStr[1][0]);
			if (empty($money)) {
				//如果什么都没有取到，那么就是CK过期了，我们需要更新下状态
				$sql = "update `pay_qrlist` set `status` ='0' where `id`='" . $id . "'";
				$DB->exec($sql);
				// $DB->query($sql);
			} else {
				$pass_file = './cache/' . md5($id) . '.tmp';
				$last = file_get_contents($pass_file);
				// echo $money;
				if ($last == '') {
					//里面似乎什么都没缓存，那只能下一次来取了
					file_put_contents($pass_file, $money);
				} else {
					//里面有余额，就可以判断订单
					if ($last != $money) {
						//我们缓存进入新的
						file_put_contents($pass_file, $money);
						//算出余额相差值
						$price = $money - $last;
						// //查询有没有未支付的订单 这里修复下之前的逻辑 要查询单独的这个通道
						// $srow = $DB->query("SELECT * FROM pay_order WHERE `pid`='{$userrow['pid']}' and trade_no='{$trade_no}' limit 1")->fetch();
						// $url = creat_callback($srow);
						// $data = get_curl($url['notify']);

						$orderrow = $DB->query("SELECT * FROM `pay_order` WHERE `price` = '" . $price . "' and `status` = '0' and `qr_id` = '" . $row['id'] . "';")->fetch();
						// //exit(print_r($orderrow));
						if ($orderrow) {
							$u = creat_callback($orderrow);
							get_curl($u['notify']);
						} else {
							//....余额只是发生了变化
						}
					}
				}
			}
		} else {
			//财付通支付
			$qqres = $thread[$i];
			$arr = json_decode($qqres, true);
			if ($arr['retcode'] != '0' && $arr['retmsg'] != 'OK') {
				$sql = "update `pay_qrlist` set `status` ='0' where `id`='" . $id . "'";
				$DB->exec($sql);
			} else {
				//获取状态
				$param = $arr['records'][0];
				$money = $param['price'] / 100; //支付金额
				$checks = $param['price'] . '-' . $param['desc'] . '-' . $param['trans_id']; //验证信息

				$pass_file = './cache/' . md5($id) . '.tmp'; //通道缓存文件
				$last = file_get_contents($pass_file);
				if ($last == '') {
					//里面没有存余额，只能下一次取
					file_put_contents($pass_file, $checks);
				} else {
					//有的话开始验证
					if ($last != $checks) {
						//有变动
						file_put_contents($pass_file, $checks);
						//开始查询有没有这个订单
						$orderrow = $DB->query("SELECT * FROM `pay_order` WHERE `price` = '" . $money . "' and `status` = '0' and `qr_id` = '" . $row['id'] . "';")->fetch();
						// //exit(print_r($orderrow));
						if ($orderrow) {
							$u = creat_callback($orderrow);
							get_curl($u['notify']);
						} else {
							//....余额只是发生了变化
						}
					} else {
						//没有变动啊
					}
				}
			}
		}
	}
}

//遍历所有正常二维码
// $rs=$DB->query("SELECT * from pay_qrlist WHERE status='1' and type!='wxpay' and crontime<'{$time}' order by rand() limit {$limit}");
// while($row = $rs->fetch())
// {	
// 	$date = check_money_notify($row,true);
// 	echo $date;
// }	
// if($_GET['Cron']){
// 	// $query_A=file_get_contents($conf['Instant_url'].'Api_Check.php?url='.$_SERVER['HTTP_HOST'].'&authcode='.$Authcode);
// 	// echo $Query_A;
// 	// $query_A=json_decode($query_A,true);
// 	// $query_B=file_get_contents($Instant_url_list[0].'Api_Check.php?url='.$_SERVER['HTTP_HOST'].'&authcode='.$Authcode);
// 	// $query_B=json_decode($query_B,true);
// 	// if($query_A or $query_B){
// 	// 	if($query_A['code']==1){
// 	// 		$_SESSION['authcode']=true;
// 	// 	}elseif($query_B['code']==1){
// 	// 		$_SESSION['authcode']=true;
// 	// 	}elseif($query_A['msg']){
// 	// 		sysmsg('<h3>'.$query_A['msg'].'</h3>',true);
// 	// 	}elseif($query_B['msg']){
// 	// 		sysmsg('<h3>'.$query_B['msg'].'</h3>',true);
// 	// 	}else{
// 	// 		sysmsg('<h3>云端链接异常</h3>',true);
// 	// 	}
// 	// }
// }
echo 'Cron Ok ' . $date;
