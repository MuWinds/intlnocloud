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

/**
 * 码子列表
**/
$title='码子列表';
include './Head.php';

$my=isset($_GET['my'])?$_GET['my']:null;
if($my=='search') {
	$sql=" `{$_GET['column']}`='{$_GET['value']}'";
	$numrows=$DB->query("SELECT * from pay_qrlist WHERE{$sql}")->rowCount();
	$con='包含 '.$_GET['value'].' 的共有 <b>'.$numrows.'</b> 条记录';
	$link='&my=search&column='.$_GET['column'].'&value='.$_GET['value'];
}else{
	$numrows=$DB->query("SELECT * from pay_qrlist WHERE 1")->rowCount();
	$sql=" 1";
	$con='共有 <b>'.$numrows.'</b> 条记录';
}


echo '
	<div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">操作中心</h3></div>
		<div class="panel-body"><form action="" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
  <div class="form-group">
    <label>条件搜索</label>
	<select name="column" class="form-control"><option value="pid">商户pid</option><option value="type">二维码类型</option><option value="id">二维码备注ID</option></select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="搜索内容">
  </div>
  <button type="submit" class="btn btn-primary">搜索</button>
</form>';


	$alipay_cookie_on=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='alipay'")->fetchColumn();
	$wxpay_cookie_on=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='wxpay'")->fetchColumn();
	$qqpay_cookie_on=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='qqpay'")->fetchColumn();
	
	$alipay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='alipay'")->fetchColumn();
	$wxpay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='wxpay'")->fetchColumn();
	$qqpay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='qqpay'")->fetchColumn();
?>	
    <br>	
	<a class="btn btn-sm btn-default">COOKIE正常码子数量:</a>
	<a class="btn btn-sm btn-default">支付宝:<?=$alipay_cookie_on?>个</a>
	<a class="btn btn-sm btn-default">微  信:<?=$wxpay_cookie_on?>个</a>
	<a class="btn btn-sm btn-default">QQ钱包:<?=$qqpay_cookie_on?>个</a>	
    <br>	
    <br>	
	<a class="btn btn-sm btn-default">COOKIE失效码子数量</a>
	<a class="btn btn-sm btn-default">支付宝:<?=$alipay_cookie_ok?>个</a>
	<a class="btn btn-sm btn-default">微  信:<?=$wxpay_cookie_ok?>个</a>
	<a class="btn btn-sm btn-default">QQ钱包:<?=$qqpay_cookie_ok?>个</a>
	<?php echo '<br><br>'.$con;?>
      </div>
      </div>
      </div>
	<div class="panel">
        <div class="panel-control"><h3 class="panel-title"><?php echo $title?></h3></div>
	  <form name="form1" id="form1">
	  <div class="table-responsive">
<?php echo $con?>
        <table class="table table-striped table-bordered table-vcenter">
          <thead><tr><th>ID/PID</th><th>类型/备注</th><th>监控余额
          		<!--th>订单数量</th>
              	<th>跑分金额</th-->
              	<th>今日订单</th>
              	<th>今日跑分</th>
              	<th>昨日订单</th>
              	<th>昨日跑分</th>
				<th>状态/更新时间/运行时间</th>
              	<th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize)
{
 $pages++;
 }
if (isset($_GET['page'])){
$page=intval($_GET['page']);
}
else{
$page=1;
}
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pay_qrlist WHERE{$sql} order by addtime desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
  $today=date("Y-m-d").' 00:00:00';
  $today2=date("Y-m-d").' 23:59:59';
  $lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
  $lastday2=date("Y-m-d",strtotime("-1 day")).' 23:59:59';
  $qr_id=$res['id'];
  // 
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
	
	echo '<tr><td>'.$res['id'].'<br/>'.$res['pid'].'</td>
	<td>'.pay_type($res['type']).'<br/>'.$res['beizhu'].'</b></td>
	<td><b>￥ '.$res['money'].'</td>
	<!--td>'.'总订单：'.$ddsl.'<br>'.'已完成：'.$zcgddsl.'<br>'.'未完成：'.$zwwcddsl.'<br>'.'成功率：'.(round((($zcgddsl?$zcgddsl:1) / ($ddsl?$ddsl:1)),2)*100).'%</td>
	<td>'.'总金额：'.$zpfddje.'<br>'.'已完成：'.$zpfcgje.'<br>'.'未完成：'.$zpfwwcje.'</td-->
	<td>'.'总订单：'.$jrzddsl.'<br>'.'已完成：'.$jrzcgddsl.'<br>'.'未完成：'.$jrzwwcddsl.'<br>'.'成功率：'.(round((($jrzcgddsl?$jrzcgddsl:1) / ($jrzddsl?$jrzddsl:1)),2)*100).'%</td>
	<td>'.'总金额：'.$jrzpfddje.'<br>'.'已完成：'.$jrzpfcgje.'<br>'.'未完成：'.$jrzpfwwcje.'</td>
	<td>'.'总订单：'.$zrzddsl.'<br>'.'已完成：'.$zrzcgddsl.'<br>'.'未完成：'.$zrzwwcddsl.'<br>'.'成功率：'.(round((($zrzcgddsl?$zrzcgddsl:1) / ($zrzddsl?$zrzddsl:1)),2)*100).'%</td>
	<td>'.'总金额：'.$zrzpfddje.'<br>'.'已完成：'.$zrzpfcgje.'<br>'.'未完成：'.$zrzpfwwcje.'</td>
	<td>'.cookie_zt($res['status'],$res['type'],$res['addtime'],$res['endtime']).'<br/>'.$res['addtime'].'<br/>'.date('Y-m-d H:i:s',($res['crontime'])).'</td>';
?>
<td><a onclick="Del_Qr('<?=$res['id']?>');" class="btn btn-xs btn-warning"><i class="fa fa-trash-o">删除此二维码</i></a></td>

<?php }?>
<tr>
          </tbody>
        </table>
      </div>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
?>
    </div>
  </div>
  <script type="text/javascript">
function Del_Qr(id) { //删除二维吗
	var confirmobj = layer.confirm('此操作将此数据，是否确定？', {
		btn: ['确定', '取消']
	},
	function() {
		var ii = layer.load(2, {
			shade: [0.1, '#fff']
		});
		$.ajax({
			type: 'POST',
			url: "Ajax.php?act=Del_Qr",
			data: {
				id
			},
			dataType: 'json',
			success: function(data) {
				layer.close(ii);
				if (data.code == 1) {
					layer.alert(data.msg, {
						icon: 1
					},
					function() {
						location.href = "?";
					});
				} else {
					layer.alert(data.msg);
				}
			},
			error: function(data) {
				layer.msg('服务器错误');
				return false;
			}
		});
	},
	function() {
		layer.close(confirmobj);
	});
}
  </script>