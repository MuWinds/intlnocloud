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
 * 商户设置
**/
$title='商户设置';
include './Head.php';

if($_GET['WIDout_trade_no'] and $_GET['WIDtotal_fee'] and $_GET['WIDsubject']){
	exit("<script language='javascript'>window.location.href='./SDK/epayapi.php?WIDout_trade_no=".$_GET['WIDout_trade_no']."&WIDsubject=".$_GET['WIDsubject']."&WIDtotal_fee=".$_GET['WIDtotal_fee']."&type=".$_GET['type']."';</script>");
}
?>
<!-- APP MAIN ==========-->
		<div class="row">      
						<div class="col-md-12 col-lg-12 col-xl-12">
                               <div class="card bg-white m-b-30">
                                        <div class="card-body new-user">
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#overview" data-toggle="tab"  class="btn btn-success mb-2 mr-2">在线充值额度</a>
											</li>
											<li>
												<a href="#edit" data-toggle="tab" class="btn btn-warning mb-2 mr-2">当前商户测试</a>
											</li>
										</ul>
										<div class="tab-content">
											<div id="overview" class="tab-pane active">		
													<div class="bk-bg-white bk-padding-top-10 bk-padding-bottom-10">
	  <div class="panel panel-info">
        <div class="panel-heading"><h3 class="panel-title">充值额度[比例:1$=<?=$conf['ed_money']?>额度]</h3></div>
		<div class="panel-body">
        <form action="./SDK/epayapi.php" method="post" target="_blank">
		  <div class="form-group">
			<div class="input-group"><div class="input-group-addon">商户订单号</div>
            <input type="text" id="WIDout_trade_no" name="WIDout_trade_no" value="<?php echo date("YmdHis").mt_rand(100,999); ?>" class="form-control" required/>
		  </div></div>
		  <div class="form-group">
			<div class="input-group"><div class="input-group-addon">商品名称</div>
			<input type="text" name="WIDsubject" value="额度充值" class="form-control">
		  </div></div>
		  <div class="form-group">
			<div class="input-group"><div class="input-group-addon">付款金额</div>
			<input type="text" name="WIDtotal_fee" value="1.00" class="form-control" required/>
		  </div></div>
		  <div class="form-group">
			<div class="input-group"><div class="input-group-addon">支付方式</div>
			<select class="form-control" name="type" id="type"><option value="alipay">支付宝</option><option value="qqpay">QQ钱包</option><option value="wxpay">微信</option></select>
		  </div></div>
		  <button class="btn btn-block btn-info" type="submit">确 认</button>
	    </form>
																	<div class="img-timeline">
	    </form>
																		</a>
																	</div>
																</div>
															</li>
														</ol>
													</div>
												</div>
											</div>
											<div id="edit" class="tab-pane updateProfile">
												<form class="form-horizontal" method="get">
													<div class="bk-bg-white bk-padding-top-10 bk-padding-bottom-10">
														
	  <div class="panel panel-info">
        <div class="panel-heading"><h3 class="panel-title">在线测试[当前商户]</h3></div>
		<div class="panel-body">
        <form action="./SDK/epayapi.php" method="post" target="_blank">
		  <div class="form-group">
			<div class="input-group"><div class="input-group-addon">商户订单号</div>
			<input type="text" name="WIDout_trade_no" value="<?php echo date("YmdHis").mt_rand(100,999); ?>" class="form-control" required/>
		  </div></div>
		  <div class="form-group">
			<div class="input-group"><div class="input-group-addon">商品名称</div>
			<input type="text" name="WIDsubject" value="测试商品" class="form-control" required/>
		  </div></div>
		  <div class="form-group">
			<div class="input-group"><div class="input-group-addon">付款金额</div>
			<input type="text" name="WIDtotal_fee" value="0.<?php echo mt_rand(01,99); ?>" class="form-control" required/>
		  </div></div>
		  <div class="form-group">
			<div class="input-group"><div class="input-group-addon">支付方式</div>
			<select class="form-control" name="type" id="type"><option value="alipay">支付宝</option><option value="qqpay">QQ钱包</option><option value="wxpay">微信</option></select>
		  </div></div>
		  <button class="btn btn-block btn-info" type="submit">确 认</button>
	    </form>
														<div class="bk-bg-white">									
																</div>
																</div>
															</div>
														</div>
													</div>
												</form>
											</div>
											</div>
											</div>
                            <div class="row">
							<div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">免挂机版</h4>
                                            <table class="table table-centered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>名称</th>
                                                        <th>状态</th>
                                                        <th>会员</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>支付宝</td>
														
                                                        <td><?php 
										if($userrow['alipay_free_vip_time']>$date){
											echo'<a style="color: blue;">开启</a>';
										}else{
											echo'<a style="color: red;">关闭</a>';
										}
										?></td>
										
                                       <td><?php 
										if($userrow['alipay_free_vip_time']>$date){?>
											<a href="#overview" class="btn btn-success mb-2 mr-2" onclick="Pay_Buy('alipay');">续期</a><a style="color: green;">到期：<?=$userrow['alipay_free_vip_time']?>
										<?php }else{ ?>
											<a href="#overview" class="btn btn-success mb-2 mr-2" onclick="Pay_Buy('alipay');">开通</a><a style="color: red;">未开通或已到期</a>
										<?php } ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>QQ钱包</td>
										 <td><?php 
										if($userrow['qqpay_free_vip_time']>$date){
											echo'<a style="color: blue;">开启</a>';
										}else{
											echo'<a style="color: red;">关闭</a>';
										}
										?></td>
										
                                       <td><?php 
										if($userrow['qqpay_free_vip_time']>$date){?>
											<a href="#overview" class="btn btn-success mb-2 mr-2" onclick="Pay_Buy('qqpay');">续期</a><a style="color: green;">到期：<?=$userrow['qqpay_free_vip_time']?>
										<?php }else{ ?>
											<a href="#overview" class="btn btn-success mb-2 mr-2" onclick="Pay_Buy('qqpay');">开通</a><a style="color: red;">未开通或已到期</a>
										<?php } ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>微信</td>
										 <td><?php 
										if($userrow['wxpay_free_vip_time']>$date){
											echo'<a style="color: blue;">开启</a>';
										}else{
											echo'<a style="color: red;">关闭</a>';
										}
										?></td>
										
                                       <td><?php 
										if($userrow['wxpay_free_vip_time']>$date){?>
											<a href="#overview" class="btn btn-success mb-2 mr-2" onclick="Pay_Buy('wxpay');">续期</a><a style="color: green;">到期：<?=$userrow['wxpay_free_vip_time']?>
										<?php }else{ ?>
											<a href="#overview" class="btn btn-success mb-2 mr-2" onclick="Pay_Buy('wxpay');">开通</a><a style="color: red;">未开通或已到期</a>
										<?php } ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> <!-- end table-responsive-->
                                    </div> <!-- end card body-->
                                    </div> <!-- end card body-->
										<div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">PC挂机版</h4>
                                            <table class="table table-centered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>通道</th>
                                                        <th>状态</th>
                                                        <th>会员</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>支付宝</td>
														
                                                         <td><?php 
										if($userrow['ssvip']==1){
											echo'<a style="color: blue;">开启</a>';
										}else{
											echo'<a style="color: red;">待开发</a>';
										}
										?></td>
										
                                       <td><?php 
										if($userrow['ssvip']==1){
											echo'<a style="color: blue;">到期：</a>'.$date;
										}else{
											echo'<a style="color: red;">待开发</a>';
										}
										?></td>
										<td><a href="#overview" class="btn btn-success mb-2 mr-2">开通/续期</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>QQ钱包</td>
										 <td><?php 
										if($userrow['ssvip']==1){
											echo'<a style="color: blue;">开启</a>';
										}else{
											echo'<a style="color: red;">待开发</a>';
										}
										?></td>
										
                                       <td><?php 
										if($userrow['ssvip']==1){
											echo'<a style="color: blue;">到期：</a>'.$date;
										}else{
											echo'<a style="color: red;">待开发</a>';
										}
										?></td>
										<td><a href="#overview" class="btn btn-success mb-2 mr-2">开通/续期</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>微信</td>
										 <td><?php 
										if($userrow['ssvip']==1){
											echo'<a style="color: blue;">开启</a>';
										}else{
											echo'<a style="color: red;">待开发</a>';
										}
										?></td>
										
                                       <td><?php 
										if($userrow['ssvip']==1){
											echo'<a style="color: blue;">时间：</a>'.$date;
										}else{
											echo'<a style="color: red;">待开发</a>';
										}
										?></td>
										<td><a href="#overview" class="btn btn-success mb-2 mr-2">开通/续期</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> <!-- end table-responsive-->
                                    </div> <!-- end card body-->
                                    </div> <!-- end card body-->
                                    </div> <!-- end card body-->
                                    </div> <!-- end card body-->
                                    </div> <!-- end card body-->
  <script type="text/javascript">
function Pay_Buy(type){//POST提交
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Pay_Buy",
				data : {type},
				dataType : 'json',
				timeout:10000,
				success : function(data) {					  
					  layer.close(ii);
					  layer.msg(data.msg);
					  if(data.code==1){
						var paymsg = '<table class="table table-condensed table-hover" id="orderItem">';
						paymsg+= '<button class="btn btn-default btn-block" onclick="window.location.href=\'SDK/epayapi.php?type=wxpay&WIDout_trade_no='+data.trade_no+'&WIDsubject='+data.name+'\'" style="margin-top:10px;"><img width="20" src="/Core/Assets/Icon/wxpay.ico" class="logo">微信</button>';
						paymsg+='<button class="btn btn-default btn-block" onclick="window.location.href=\'SDK/epayapi.php?type=alipay&WIDout_trade_no='+data.trade_no+'&WIDsubject='+data.name+'\'" style="margin-top:10px;"><img width="20" src="/Core/Assets/Icon/alipay.ico" class="logo">支付宝</button>';
						paymsg+='<button class="btn btn-default btn-block" onclick="window.location.href=\'SDK/epayapi.php?type=qqpay&WIDout_trade_no='+data.trade_no+'&WIDsubject='+data.name+'\'" style="margin-top:10px;"><img width="20" src="/Core/Assets/Icon/qqpay.ico" class="logo">QQ钱包</button>';
						
						layer.alert('<center><h2>￥ '+data.money+'</h2><hr>'+paymsg+'<hr>提示：支付完成后即可直接到账</center>',{
							btn: ['关闭此页面'],
							title:'开/续一月['+data.type_name+']免挂会员支付页',
							skin: 'layui-layer-rim',
							closeBtn: true /*false*/
							});
						}
						
						/*
						var area = [$(window).width() > 480 ? '480px' : '100%'];
						layer.open({
							type: 1,
							area: area,
							//title:'开/续一月['+data.type_name+']免挂会员支付页',
							title: '二维码详细信息',
							skin: 'layui-layer-rim',
							content: paymsg
						});
						*/
				},
				error:function(data){
					layer.close(ii);
					layer.msg('服务器错误');
					}
			});
}
  </script>
<?php
	include './Foot.php';
?>