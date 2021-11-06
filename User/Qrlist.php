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
 * 码子管理
**/
$title='码子管理';
include './Head.php';

$my=isset($_GET['my'])?$_GET['my']:null;
if($my=='search') {
	$sql="`pid`='{$userrow['pid']}' and `{$_GET['column']}`='{$_GET['value']}'";
	$numrows=$DB->query("SELECT * from pay_qrlist WHERE{$sql}")->rowCount();
	$con='包含 '.$_GET['value'].' 的共有 <b>'.$numrows.'</b> 条记录';
	$link='&my=search&column='.$_GET['column'].'&value='.$_GET['value'];
}else{
	$numrows=$DB->query("SELECT * from pay_qrlist WHERE `pid`='{$userrow['pid']}'")->rowCount();
	$sql=" `pid`='{$userrow['pid']}'";
	$con='共有 <b>'.$numrows.'</b> 条记录';
}


	$alipay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='alipay' and `pid`='{$userrow['pid']}'")->fetchColumn();
	$wxpay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='wxpay' and `pid`='{$userrow['pid']}'")->fetchColumn();
	$qqpay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='qqpay' and `pid`='{$userrow['pid']}'")->fetchColumn();
	
	$alipay_cookie_no=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='alipay' and `pid`='{$userrow['pid']}'")->fetchColumn();
	$wxpay_cookie_no=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='wxpay' and `pid`='{$userrow['pid']}'")->fetchColumn();
	$qqpay_cookie_no=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='qqpay' and `pid`='{$userrow['pid']}'")->fetchColumn();
	
?>	
 <div id="content" class="app-content" role="main">
    <div class="app-content-body ">
		<div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					</div>
					<div class="modal-body">
有CK失效或未更新CK的二维码,请去更新!
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
		</div>								<!-- Modal Info -->
								   <div class="modal fade bs-example-modal-center"   id="modalHeaderColorInfo" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                         <div class="modal-dialog modal-dialog-centered">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <h5 class="modal-title" id="exampleModalLabel">添加二维码</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                 <span aria-hidden="true">&times;</span>
                                                 </button>
                                                  </div>
													<div class="modal-body">
													<div class="form-group">
<label>支付类型(选择好类型再上传二维码):</label><br><select class="form-control" name="type" id="type"><option value="alipay">支付宝-><?php if($userrow['alipay_free_vip_time']>$date){echo'会员：'.$userrow['alipay_free_vip_time'];}else{echo'非会员无法添加二维码';}?></option><option value="qqpay">QQ钱包-><?php if($userrow['qqpay_free_vip_time']>$date){echo'会员：'.$userrow['qqpay_free_vip_time'];}else{echo'非会员无法添加二维码';}?></option><option value="wxpay">微信-><?php if($userrow['wxpay_free_vip_time']>$date){echo'会员：'.$userrow['wxpay_free_vip_time'];}else{echo'非会员无法添加二维码';}?></option></select>
</div>
<div class="form-group">
<label>选择二维码(剪切好边框再上传哦):</label><span class="glyphicon glyphicon-qrcode"></span>
<label for="file"></label><input type="file" id="imgfile" accept="image/*" multiple>
</div>
<div class="form-group">
<label>二维码地址:</label><br>
<input type="text" class="form-control" id="qr_url" value="" placeholder="上传二维码自动识别,可修改" name="qr_url">
</div>
<div class="form-group">
<label id="beizhu_name">备注:</label><br>
<input type="text" class="form-control" id="beizhu" value="" placeholder="" name="beizhu">
</div>
<div class="form-group">
<label id="LoginQrcode_msg">确保所有信息填写正确哦</label><br><div id="LoginQrcode">
</div>
<a id="Wx_Sumbit"></a>			
													</div>
												</div>
												</div>
      </div>
    </div>
  </div>
  
  
								   <div class="modal fade bs-example-modal-center"   id="modalHeaderColorInfo_Up_Qr_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                         <div class="modal-dialog modal-dialog-centered">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <h5 class="modal-title" id="exampleModalLabel">更新二维码</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                 <span aria-hidden="true">&times;</span>
                                                 </button>
                                                  </div>
													<div class="modal-body">
													<input type="hidden" name="Up_id" id="Up_id" value=""/>
													<!--div class="form-group">
                                                                <label for="password1">二维码Id：</label>
                                                                <input class="form-control" type="text" name="Up_id" id="Up_id" disabled>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="password1">二维码类型：</label>
                                                                <input class="form-control" type="text" name="Up_type" id="Up_type" disabled>
                                                            </div>
                                                            
                                                            <!--div class="form-group">
                                                                <label for="password1" id="Up_beizhu_name">二维码备注：</label>
                                                                <input class="form-control" type="text" name="Up_beizhu" id="Up_beizhu" disabled>
                                                            </div-->
    
                                                           <div class="form-group">
                                                           <label id="Up_LoginQrcode_msg">请用对应二维码的支付宝或QQ扫码哦</label><br><div id="Up_LoginQrcode">
                                                           </div>
															<a id="Up_Wx_Sumbit"></a>		
													</div>
												</div>
												</div>
      </div>
    </div>
  </div>
	
<!-- End Page Header --> 
                            <div class="row">
                                 <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div>               
 <!--div class="col-lg-8">
<form action="" method="GET" class="form-inline"><input type="hidden" name="my" value="search">  
  <div class="form-group">
    <label>条件搜索</label>
	<select name="column" class="form-control"><option value="beizhu">二维码备注</option><option value="type">二维码类型</option><option value="id">二维码备注ID</option></select>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="搜索内容">
  </div>
  <button type="submit" class="btn btn-primary">搜索</button>
</form>
<br>	
</div-->		
	<b class="btn btn-sm btn-default">正常:
	<b class="btn btn-sm btn-default">支付宝:<a style="color: blue;"><?=$alipay_cookie_ok?></a>个</b>
	<b class="btn btn-sm btn-default">微  信:<a style="color: blue;"><?=$wxpay_cookie_ok?></a>个</b>
	<b class="btn btn-sm btn-default">QQ钱包:<a style="color: blue;"><?=$qqpay_cookie_ok?></a>个</b></b>
    <br>	
	<b class="btn btn-sm btn-default">失效:
	<b class="btn btn-sm btn-default">支付宝:<a style="color: red;"><?=$alipay_cookie_no?></a>个</b>
	<b class="btn btn-sm btn-default">微  信:<a style="color: red;"><?=$wxpay_cookie_no?></a>个</b>
	<b class="btn btn-sm btn-default">QQ钱包:<a style="color: red;"><?=$qqpay_cookie_no?></a>个</b>	</b>
    <br>   
	<a data-toggle="modal" class="modal-basic" href="#modalHeaderColorInfo_Up_Qr_modal" data-target="#modalHeaderColorInfo_Up_Qr_modal" data-id="modalHeaderColorInfo_Up_Qr_modal" id="Up_Qr_modal"></a>            
													</div>
                                                    <div class="form-group mb-2">    
                                                <div class="text-lg-right">
                                                    <button type="submit"  class="btn btn-success mb-2 mr-2"><i class="mdi mdi-bolnisi-cross"></i> <?php echo $con;?></button>
													<button data-toggle="modal"  class="btn btn-info mb-2 mr-2" href="#modalHeaderColorInfo" data-target="#modalHeaderColorInfo" data-id="modalHeaderColorInfo"><i class="fa fa-plus"></i> 新增通道 (当前：<a style="color: blue;"><?=$DB->query("SELECT * from pay_qrlist WHERE `pid`='{$userrow['pid']}'")->rowCount()?></a>/最大：<a style="color: blue;"><?=$conf['qr_nums']?></a>)</button>
												</div>
                                            </div><!-- end col-->
										<div class="table-responsive">
                                            <table class="table table-bordered text-nowrap">
          <thead>
												<tr>
													<th>#</th>
													<th>数据</th>
													<th>更新时间/运行时间</th>
													</tr>
													</thead>
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
	echo '<tr><td><b><a href="javascript:showQrlist(\''.$res['id'].'\')" title="点击查看详情">'.$res['id'].'</a></b></td>
	<td><img src="/Core/Assets/Icon/'.$res['type'].'.ico" width="16" onerror="this.style.display=\'none\'">'.pay_type($res['type']).'<br/>'.$res['beizhu'].'<br/>'.($res['type']=='wxpay'?'不支持此功能':'<b>￥ '.$res['money']).'</b></br><a href="javascript:showQrlist(\''.$res['id'].'\')" title="点击查看详情">查看详细</a></td>
	<td>'.cookie_zt($res['status'],$res['type'],$res['addtime'],$res['endtime']).($res['type']=='alipay'?'<br/><font color=#00E3E3>'.$res['data_data'].'</font>':'').'<br/>'.$res['addtime'].'<br/>'.date('Y-m-d H:i:s',($res['crontime'])).'</br>';
	if($res['type']=='pay'){?>
		<a href="#Up_Ali_Qr_modal" onclick="Get_Ali_Qr('<?=$res['id']?>');" class="btn btn-xs btn-success"><i class="fa fa-arrow-circle-o-up"></i> 更新</a>
	<?php }else{?>
		<a href="#Up_Qr_modal" onclick="Get_Qr('<?=$res['id']?>');" class="btn btn-xs btn-success"><i class="fa fa-arrow-circle-o-up"></i> 更新</a>
	<?php }?>
	&nbsp;<a onclick="Del_Qr('<?=$res['id']?>');" class="btn btn-xs btn-warning"><i class="fa fa-trash-o"></i></a></td>
	</td>
	<tr>
<?php
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
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div>
<?php
	include './Foot.php';
?>

		<script>
	<?php if($alipay_cookie_no or $qqpay_cookie_no or $wxpay_cookie_no){?>
	$('#myModal').modal('show');
	<?php }?>
function showQrlist(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	var status = ['<span class="label label-primary">未支付</span>','<span class="label label-success">已支付</span>','<span class="label label-red">已退款</span>'];
	$.ajax({
		type : 'GET',
		url : 'Ajax.php?act=Qrlist&id='+id,
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var data = data.data;
				var item = '<table class="table table-condensed table-hover" id="orderItem">';
				item += '<tr><td class="info">监控金额</td class="orderTitle"><td colspan="5" class="orderContent">'+data.money+'</a></td>';
				item += '<tr class="orderTitle"><td class="info" class="orderTitle">二维码类型</td><td colspan="5" class="orderContent">'+data.type+'</td></tr>';
				item += '<tr><td class="info" class="orderTitle">总订单详细</td><td colspan="5" class="orderContent">'+data.ali_order+'</td></tr>';
				item += '<tr><td class="info" class="orderTitle">今日详细</td><td colspan="5" class="orderContent">'+data.jr_order+'</td></tr>';
				item += '<tr><td class="info" class="orderTitle">昨日详细</td><td colspan="5" class="orderContent">'+data.zr_order+'</td></tr>';
				item += '</table>';
				var area = [$(window).width() > 480 ? '480px' : '100%'];
				layer.open({
				  type: 1,
				  area: area,
				  title: '二维码详细信息',
				  skin: 'layui-layer-rim',
				  content: item
				});
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
 $('#shanchu').on('show.bs.modal', function (event) {
      var btnThis = $(event.relatedTarget); //触发事件的按钮
      var modal = $(this);  //当前模态框
      var modalId = btnThis.data('id');   //解析出data-id的内容
      var content = btnThis.closest('tr').find('td').eq(0).text();
      modal.find('.ca0').val(content); 
      var content = btnThis.closest('tr').find('td').eq(6).text();
      modal.find('.ca6').val(content);
	 
});
		</script>
<script src="Core_Js/Qrlist.js"></script>