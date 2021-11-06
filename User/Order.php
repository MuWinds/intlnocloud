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
 * 订单列表
**/
$title='订单列表';
include './Head.php';
$my=isset($_GET['my'])?$_GET['my']:$_POST['my'];

if($my=='search') {
	$sql=" `{$_GET['column']}`='{$_GET['value']}'";
	$numrows=$DB->query("SELECT * from pay_order WHERE `pid`='{$userrow['pid']}' and{$sql}")->rowCount();
	$con='包含 '.$_GET['value'].' 的共有 <b>'.$numrows.'</b> 条记录';
	$link='&my=search&column='.$_GET['column'].'&value='.$_GET['value'];
}elseif($my=='date_search'){//搜索
	$adddate = $_POST['adddate'];
	$enddate = $_POST['enddate'];
	$orders_today=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<='{$enddate}'")->fetchColumn();
	$orders_ali=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<='{$enddate}' and status=1 and type='alipay'")->fetchColumn();
	$orders_wx=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<='{$enddate}' and status=1 and type='wxpay'")->fetchColumn();
	$orders_qq=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<='{$enddate}' and status=1 and type='qqpay'")->fetchColumn();
	$orders_today1=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<='{$enddate}' and status=1")->fetchColumn();

	$alipay_money=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<='{$enddate}' and status=1 and type='alipay'")->fetchColumn();
	$wxpay_money=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<='{$enddate}' and status=1 and type='wxpay'")->fetchColumn();
	$qqpay_money=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<='{$enddate}' and status=1 and type='qqpay'")->fetchColumn();
	$all_money=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='{$adddate}' and addtime<'{$enddate}' and status=1")->fetchColumn();
	
	$alipay_money=$alipay_money?sprintf("%.2f",$alipay_money):'0.00';
	$wxpay_money=$wxpay_money?sprintf("%.2f",$wxpay_money):'0.00';
	$qqpay_money=$settle_yes?sprintf("%.2f",$qqpay_money):'0.00';
	$all_money=$settle_no?sprintf("%.2f",$all_money):'0.00';
			
	$sql=" addtime>='{$adddate}' and addtime<='{$enddate}'";
	$numrows=$DB->query("SELECT * from pay_order WHERE `pid`='{$userrow['pid']}' and{$sql}")->rowCount();
	$con='这个时间段共有 <b>'.$numrows.'</b> 条记录';
	$link='&my=date_search&adddate='.$adddate.'&enddate='.$enddate;
}else{
	$numrows=$DB->query("SELECT * from pay_order WHERE `pid`='{$userrow['pid']}' and 1")->rowCount();
	$sql=" `pid`='{$userrow['pid']}'";
	$con='共有 <b>'.$numrows.'</b> 条记录';
}

	$alipay_cookie_on=$DB->query("SELECT count(*) from pay_qrlist WHERE `pid`='{$userrow['pid']}' and status='1' and type='alipay'")->fetchColumn();
	$wxpay_cookie_on=$DB->query("SELECT count(*) from pay_qrlist WHERE `pid`='{$userrow['pid']}' and status='1' and type='wxpay'")->fetchColumn();
	$qqpay_cookie_on=$DB->query("SELECT count(*) from pay_qrlist WHERE `pid`='{$userrow['pid']}' and status='1' and type='qqpay'")->fetchColumn();
	
	$alipay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist WHERE `pid`='{$userrow['pid']}' and status='2' and type='alipay'")->fetchColumn();
	$wxpay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist WHERE `pid`='{$userrow['pid']}' and status='2' and type='wxpay'")->fetchColumn();
	$qqpay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist WHERE `pid`='{$userrow['pid']}' and status='2' and type='qqpay'")->fetchColumn();
?>
<style>
#orderItem .orderTitle{word-break:keep-all;}
#orderItem .orderContent{word-break:break-all;}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
.form-inline .form-group {
    display: inline-block;
    margin-bottom: 0;
    vertical-align: middle;
}
</style>
	
<!-- End Page Header -->
                            <div class="row">
                                 <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div>
<form action="" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
  <div class="form-group">
	<select name="column" class="form-control"><option value="out_trade_no">商户订单</option><option value="trade_no">订单号</option><option value="pid">商户号</option><option value="name">商品名称</option><option value="money">金额</option><option value="type">支付方式</option></select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="搜索内容">
  </div>
  <div class="form-group">
  <button type="submit" class="btn btn-primary">条件搜索</button>
</form>
 </div>
<form action="" method="POST" class="form-inline"><input type="hidden" name="my" value="date_search">
  <div class="form-group">
	<input type="text" class="form-control" name="adddate" value="<?=$date?>" placeholder="<?=$date?>" style="font-size: 5px; width: 100%; color: #1A1A1A; text-align: center;">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="enddate" value="<?=$date?>" placeholder="<?=$date?>" style="font-size: 5px; width: 100%; color: #1A1A1A; text-align: center;">
  </div>
  <div class="form-group">
  <button type="submit" class="btn btn-primary">时段搜索</button>
</form>
<?php if($_POST['my']=='date_search'){?>
	<br>
	<a class="btn btn-sm btn-default">订单数:<?=$orders_today?></a>
	<a class="btn btn-sm btn-default">支付宝:<?=$orders_ali?></a>
	<a class="btn btn-sm btn-default">微信:<?=$orders_wx?></a>
	<a class="btn btn-sm btn-default">QQ:<?=$orders_qq?></a>
	<a class="btn btn-sm btn-default">成功总数:<?=$orders_today1?></a>	
	<br>
	<br>
	<a class="btn btn-sm btn-default">金额数据</a>
	<a class="btn btn-sm btn-default">支付宝:￥<?=$alipay_money?></a>
	<a class="btn btn-sm btn-default">微信:￥<?=$wxpay_money?></a>
	<a class="btn btn-sm btn-default">QQ:￥<?=$qqpay_money?></a>
	<a class="btn btn-sm btn-default">总:￥<?=$all_money?></a>
	<br>
	<?php }?>
													</div>  
													</div>
                                                    <div class="form-group mb-2">    
                                                <div class="text-lg-right">
                                                    <button type="submit"  class="btn btn-success mb-2 mr-2"><i class="mdi mdi-bolnisi-cross"></i> <?php echo $con;?></button>
													<button data-toggle="modal"  class="btn btn-warning mb-2 mr-2" href="#modalHeaderColorInfo" data-target="#modalHeaderColorInfo" data-id="modalHeaderColorInfo"><i class="fa fa-plus"></i> 保留最近5天订单</button>
												</div>
                                            </div><!-- end col-->
										<div class="table-responsive">
                                            <table class="table table-bordered text-nowrap">
          <thead><tr><th>交易号/商户订单号</th><th>商品名称/商户PID</th><th>商品金额/浮动金额</th><th>二维码ID/二维码备注</th><th>创建时间/完成时间</th><th>支付方式/支付状态</th><th>操作</th></tr></thead>
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

$rs=$DB->query("SELECT * FROM pay_order WHERE `pid`='{$userrow['pid']}' and{$sql} order by addtime desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
	$row=$DB->query("SELECT * FROM `pay_qrlist` WHERE `pid`='{$userrow['pid']}' and `id`='{$res['qr_id']}' limit 1")->fetch();
	echo '<tr><td>'.$res['trade_no'].'<br/>'.$res['out_trade_no'].'</td>
	<td>'.$res['name'].'<br/><b>'.$res['pid'].'</b></td>
	<td><b>￥ '.$res['money'].'<br/>￥'.$res['price'].'</b></td>
	<td>'.$res['qr_id'].'→'.($row['beizhu']?$row['beizhu']:'无备注').'</td>
	<td>'.$res['addtime'].'<br/>'.$res['endtime'].'</td>
    <td><img src="/Core/Assets/Icon/'.$res['type'].'.ico" width="16" onerror="this.style.display=\'none\'">'.pay_type($res['type']).'<br>'.order_zt($res['status']).'</td>';?>
	<td><a onclick="Instant_Notify('<?=$res['trade_no']?>');" class="btn btn-danger btn-xs">补单回调</a></td> 
	<?php echo '</tr>';
}
?>
                                                </tbody>
                                            </table>
																						<nav style="float: inline-end;">
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li class="page-item"><a class="page-link" href="?page='.$first.$link.'">首页</a></li>';

} else {
echo '<li class="page-item"><a class="page-link">首页</a></li>';

}
for ($i=1;$i<$page;$i++)
echo '<li class="page-item"><a class="page-link" href="?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="page-item active"><a class="page-link">'.$page.'</a></li>';
if($pages>=10)$s=10;
else $s=$pages;
for ($i=$page+1;$i<=$s;$i++)
echo '<li class="page-item"><a class="page-link" href="?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{

echo '<li class="page-item"><a class="page-link" href="?page='.$last.$link.'">尾页</a></li>';
} else {

echo '<li class="page-item"><a class="page-link">尾页</a></li>';
}
echo'</ul>';
#分页
?>
                                                </nav>
                                        </div> <!-- end table-responsive-->
  <script type="text/javascript">
  function Instant_Notify(trade_no) { //补单回调
	var confirmobj = layer.confirm('此操作将会给对接系统充值到账，是否确定？', {
		btn: ['确定', '取消']
	},
	function() {
		var ii = layer.load(2, {
			shade: [0.1, '#fff']
		});
		$.ajax({
			type: 'POST',
			url: "Ajax.php?act=Instant_Notify",
			data: {
				trade_no
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
                                                </nav>
                                        </div> <!-- end table-responsive-->
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div>
<?php
	include './Foot.php';
?>