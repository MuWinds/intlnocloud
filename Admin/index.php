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
$title='后台管理';
include './Head.php';

// $checkServerSatatus = checkServerSatatus($conf['Instant_url']);

$http_runtime = $_SESSION['http_runtime'];
setcookie("Query_row", 'Zero_Yas', time() + 1800);

$count1=$DB->query("SELECT count(*) from pay_order")->fetchColumn();
$count2=$DB->query("SELECT count(*) from pay_user")->fetchColumn();
$count3=$DB->query("SELECT count(*) from pay_qrlist")->fetchColumn();
//settle
$count4=$DB->query("SELECT sum(money) from pay_order")->fetchColumn();
$count5=$DB->query("SELECT sum(money) from pay_order where status='1'")->fetchColumn();
$count6=$DB->query("SELECT sum(money) from pay_order where status='0'")->fetchColumn();


$lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
$today=date("Y-m-d").' 00:00:00';
$rs=$DB->query("SELECT * from pay_order where status=1 and endtime>='$today'");
$order_today=array('alipay'=>0,'tenpay'=>0,'qqpay'=>0,'wxpay'=>0,'all'=>0);
while($row = $rs->fetch())
{
	$order_today[$row['type']]+=$row['money'];
}
$order_today['all']=$order_today['alipay']+$order_today['tenpay']+$order_today['qqpay']+$order_today['wxpay'];

$rs=$DB->query("SELECT * from pay_order where status=1 and endtime>='$lastday' and endtime<'$today'");
$order_lastday=array('alipay'=>0,'qqpay'=>0,'wxpay'=>0,'all'=>0);
while($row = $rs->fetch())
{
	$order_lastday[$row['type']]+=$row['money'];
}
$order_lastday['all']=$order_lastday['alipay']+$order_lastday['tenpay']+$order_lastday['qqpay']+$order_lastday['wxpay'];

$data['order_today']=$order_today;
$data['order_lastday']=$order_lastday;

			$orders=$DB->query("SELECT * from pay_order")->rowCount();
			$order_yes=$DB->query("SELECT * from pay_order WHERE status=1")->rowCount();
			$order_no=$DB->query("SELECT * from pay_order WHERE status!=1")->rowCount();
			
			$lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
			$today=date("Y-m-d").' 00:00:00';
			
			$orders_lastday=$DB->query("SELECT count(*) from pay_order WHERE addtime>='$today'")->fetchColumn();
			$orders_lastday_yes=$DB->query("SELECT count(*) from pay_order WHERE status=1 and addtime>='$today'")->fetchColumn();
			$orders_lastday_no=$DB->query("SELECT count(*) from pay_order WHERE status!=1 and addtime>='$today'")->fetchColumn();
			
			
			$orders_today=$DB->query("SELECT count(*) from pay_order WHERE addtime>='$lastday' and addtime<='$today'")->fetchColumn();
			$orders_today_yes=$DB->query("SELECT count(*) from pay_order WHERE status=1 and addtime>='$lastday' and addtime<='$today'")->fetchColumn();
			$orders_today_no=$DB->query("SELECT count(*) from pay_order WHERE status!=1 and addtime>='$lastday' and addtime<='$today'")->fetchColumn();
			
			
			
			$moneys=$DB->query("SELECT sum(money) from pay_order WHERE status=1")->fetchColumn();
			$moneys_lastday=$DB->query("SELECT sum(money) from pay_order WHERE status=1 and addtime>='$today'")->fetchColumn();
			$moneys_today=$DB->query("SELECT sum(money) from pay_order WHERE status=1 and addtime>='$lastday' and addtime<='$today'")->fetchColumn();
			
			$moneys=$moneys?sprintf("%.2f",$moneys):'0.00';
			$moneys_lastday=$moneys_lastday?sprintf("%.2f",$moneys_lastday):'0.00';
			$moneys_today=$moneys_today?sprintf("%.2f",$moneys_today):'0.00';
			

?>
<div class="row">
<div class="col-xs-12 col-lg-12">
	  <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">监控</h3></div>
		<div class="panel-body">
<p>以下网址可以设置2-6秒的监控速度,必须监控，因为已经本地化了，不监控就不会回调</p>
<p>QQ、支付宝监控以下网址(微信无需监控)</p>
<li class="list-group-item">http://<?php echo $_SERVER['HTTP_HOST']?>/Cron.php</li>
<br>
<li class="list-group-item" href="Set.php?mod=cron"><a href="Set.php?mod=cron" class="btn btn-xs btn-info">点此查看宝塔1分钟以下监控方法</a></li>
</div>
</div>
<div class="row">
    <div class="col-xs-12 col-lg-8">
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title" id="title">后台管理首页</h3></div>
        <table class="table table-bordered">
			<tr height="25">
			<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-tint"></span>订单总数：</b></br><span id="count1"><?php echo $count1?></span>条</font></td>
			<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-check"></i>商户总数：</b></br></span><span id="count2"><?php echo $count2?></span>个</font></td>
			<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-exclamation-sign"></i>码子总数：</b></span></br><span id="count3"><?php echo ($count3)?></span>个</font></td>
			</tr>
			<tr height="25">
			<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-usd"></i>总金额：</b></br></span><span id="count5"><?php echo $count4?></span>元</font></td>
			<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-usd"></i>完成金额：</b></br><span id="yxts"><?php echo $count5?></span>元</font></td>
			<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-usd"></span>失败金额：</b></br><span id="count4"><?php echo $count6?></span>元</font></td>
			</tr>
          </table>
	    </div>
          <table class="table table-bordered table-striped">
		    <thead><tr><th class="success">订单收入统计</th><th>支付宝</th><th>微信支付</th><th>QQ钱包</th><th>总计</th></thead>
            <tbody>
			  <tr><td>今日</td><td><?php echo round($data['order_today']['alipay'],2)?></td><td><?php echo round($data['order_today']['wxpay'],2)?></td><td><?php echo round($data['order_today']['qqpay'],2)?></td><td><?php echo round($data['order_today']['all'],2)?></td></tr>
			  <tr><td>昨日</td><td><?php echo round($data['order_lastday']['alipay'],2)?></td><td><?php echo round($data['order_lastday']['wxpay'],2)?></td><td><?php echo round($data['order_lastday']['qqpay'],2)?></td><td><?php echo round($data['order_lastday']['all'],2)?></td></tr>
			</tbody>
          </table>
	    </div>
	<div class="col-xs-12 col-lg-4">
      <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title" id="title">管理员信息</h3></div>
          <ul class="list-group text-center">
		<div class="form-group">
            <li class="list-group-item">
		  <div class="input-group"><div class="input-group-addon">云端响应速度</div>
			<pre class="form-control">[本地服务器] (0ms)</pre>
	    </div></div>
		</li>
            <li class="list-group-item">
			<img src="<?php echo ($conf['qq'])?'//q2.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$conf['qq'].'&src_uin='.$conf['qq'].'&fid='.$conf['qq'].'&spec=100&url_enc=0&referer=bu_interface&term_type=PC':'//q2.qlogo.cn/headimg_dl?bs=qq&dst_uin=2109877665&src_uin=2109877665&fid=2109877665&spec=100&url_enc=0&referer=bu_interface&term_type=PC'?>" alt="avatar" class="img-circle img-thumbnail"></br>
			<span class="text-muted"><strong>用户名：</strong><font color="blue"><?php echo $conf['admin_user']?></font></span><br/><span class="text-muted"><strong>用户权限：</strong><font color="orange">管理员</font></span></li>
			<li class="list-group-item"><a href="../" class="btn btn-xs btn-default">返回首页</a>&nbsp;<a href="./Set.php?mod=account" class="btn btn-xs btn-info">修改密码</a>&nbsp;<a href="./Login.php?logout" class="btn btn-xs btn-danger">退出登录</a>
			</li>
          </ul>
      </div>
	</div>
</div>
      <!--
	  <div class="panel panel-primary">
	  <div class="panel-body">
          <h4><span class="glyphicon glyphicon-info-sign"></span> 注意事项</h4>
1.每笔交易会有手续费<br/>2.请确保结算信息，否则资金可能无法成功到账！<br/>3.系统集成，问题反馈请联系客服
        </div>
      </div-->
	  <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">服务器数据</h3></div>
		<div class="panel-body">
        <ul class="list-group">
		<li class="list-group-item">
			<b>PHP 版本：</b><?php echo phpversion() ?>
			<?php if(ini_get('safe_mode')) { echo '线程安全'; } else { echo '非线程安全'; } ?>
		</li>
		<li class="list-group-item">
			<b>MySQL 版本：</b><?php $DB_VERSION = $DB->query("select VERSION()")->fetch(); echo $DB_VERSION[0]; ?>
		</li>
		<li class="list-group-item">
			<b>服务器软件：</b><?php echo $_SERVER['SERVER_SOFTWARE'] ?>
		</li>
	</ul>
		</div>
      </div>
    </div>
  </div>