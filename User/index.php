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

$title='商户中心';
include './Head.php';

$count1=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' ")->fetchColumn();
$count2=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and status='1'")->fetchColumn();
$count3=$DB->query("SELECT count(*) from pay_qrlist WHERE `pid`='{$userrow['pid']}' ")->fetchColumn();
//settle
$count4=$DB->query("SELECT sum(money) from pay_order")->fetchColumn();
$count5=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and status='1'")->fetchColumn();
$count6=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and status='0'")->fetchColumn();


$lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
$today=date("Y-m-d").' 00:00:00';
$rs=$DB->query("SELECT * from pay_order WHERE `pid`='{$userrow['pid']}' and status=1 and endtime>='$today'");
$order_today=array('alipay'=>0,'tenpay'=>0,'qqpay'=>0,'wxpay'=>0,'all'=>0);
while($row = $rs->fetch())
{
	$order_today[$row['type']]+=$row['money'];
}
$order_today['all']=$order_today['alipay']+$order_today['tenpay']+$order_today['qqpay']+$order_today['wxpay'];

$rs=$DB->query("SELECT * from `pay_order` WHERE `pid`='{$userrow['pid']}' and `status`=1 and `endtime`>='{$lastday}' and `endtime`<'{$today}'");
$order_lastday=array('alipay'=>0,'qqpay'=>0,'wxpay'=>0,'all'=>0);
while($row = $rs->fetch())
{
	$order_lastday[$row['type']]+=$row['money'];
}
$order_lastday['all']=$order_lastday['alipay']+$order_lastday['tenpay']+$order_lastday['qqpay']+$order_lastday['wxpay'];

$data['order_today']=$order_today;
$data['order_lastday']=$order_lastday;

			$orders=$DB->query("SELECT * from pay_order")->rowCount();
			$order_yes=$DB->query("SELECT * from pay_order WHERE `pid`='{$userrow['pid']}' and status=1")->rowCount();
			$order_no=$DB->query("SELECT * from pay_order WHERE `pid`='{$userrow['pid']}' and status!=1")->rowCount();
			
			$lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
			$today=date("Y-m-d").' 00:00:00';
			
			$orders_lastday=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='$today'")->fetchColumn();
			$orders_lastday_yes=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and status=1 and addtime>='$today'")->fetchColumn();
			$orders_lastday_no=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and status!=1 and addtime>='$today'")->fetchColumn();
			
			
			$orders_today=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and addtime>='$lastday' and addtime<='$today'")->fetchColumn();
			$orders_today_yes=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and status=1 and addtime>='$lastday' and addtime<='$today'")->fetchColumn();
			$orders_today_no=$DB->query("SELECT count(*) from pay_order WHERE `pid`='{$userrow['pid']}' and status!=1 and addtime>='$lastday' and addtime<='$today'")->fetchColumn();
			
			
			
			$moneys=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and status=1")->fetchColumn();
			$moneys_lastday=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and status=1 and addtime>='$today'")->fetchColumn();
			$moneys_today=$DB->query("SELECT sum(money) from pay_order WHERE `pid`='{$userrow['pid']}' and status=1 and addtime>='$lastday' and addtime<='$today'")->fetchColumn();
			
			$moneys=$moneys?sprintf("%.2f",$moneys):'0.00';
			$moneys_lastday=$moneys_lastday?sprintf("%.2f",$moneys_lastday):'0.00';
			$moneys_today=$moneys_today?sprintf("%.2f",$moneys_today):'0.00';
			

  $today=date("Y-m-d").' 00:00:00';
  $today2=date("Y-m-d").' 23:59:59';
  //支付宝
  $ali_a=$DB->query("SELECT count(*) from pay_order where type='alipay' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //QQ钱包
  $qq_a=$DB->query("SELECT count(*) from pay_order where type='qqpay' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //微信
  $wx_a=$DB->query("SELECT count(*) from pay_order where type='wxpay' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  
  //支付宝
  $ali_b=$DB->query("SELECT count(*) from pay_order where type='alipay' and status='1' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //QQ钱包
  $qq_b=$DB->query("SELECT count(*) from pay_order where type='qqpay' and status='1' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
  //微信
  $wx_b=$DB->query("SELECT count(*) from pay_order where type='wxpay' and status='1' and addtime>='$today' and addtime<='$today2'")->fetchColumn();
?>
 <div id="content" class="app-content" role="main">
    <div class="app-content-body ">
		<div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					</div>
					<div class="modal-body">
<?php echo $conf['modal']?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
		</div>
<!-- Loader -->

 
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- Profile -->
                                <div class="card bg-primary">
                                    <div class="card-body profile-user-box">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="media">
                                                    <span class="float-left m-2 mr-4"><img src="<?php echo ($userrow['qq'])?'//q3.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$userrow['qq'].'&src_uin='.$userrow['qq'].'&fid='.$userrow['qq'].'&spec=100&url_enc=0&referer=bu_interface&term_type=PC':'/assets/images/team-1.jpg'?>" style="height: 100px;" alt="" class="rounded-circle img-thumbnail"></span>
                                                    <div class="media-body">

                                                        <h4 class="mt-1 mb-1 text-white"><?php echo $userrow['username'];?></h4>
                                                        <p class="font-13 text-white-50"><?php echo getQQNick($userrow['key']);?></p>

                                                        <ul class="mb-0 list-inline text-light">
                                                            <li class="list-inline-item mr-3">
                                                                <h5 class="mb-1">¥ <?php echo $userrow['money'];?></h5>
                                                                <p class="mb-0 font-13 text-white-50" style="font-size: 6px;">当前额度</p>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <h5 class="mb-1"><?php 
								if($userrow['alipay_free_vip_time']>$date or $userrow['qqpay_free_vip_time']>$date or $userrow['wxpay_free_vip_time']>$date){
									echo'免挂会员';	
								}else{
									echo'非会员';	
								}?></h5>
                                                                <p class="mb-0 font-13 text-white-50" style="font-size: 6px;">会员权限</p>
                                                            </li>
                                                        </ul>
					<p>腾讯QQ快捷登录:<code><?php if($userrow['nickname']){echo '已绑定['.$userrow['nickname'].']<a href="Social.php?unbind=1" class="btn btn-warning mb-2 mr-2">解绑</a>';}else{echo '未绑定<a href="Social.php" class="btn btn-success mb-2 mr-2">绑定</a>';}?></code></p>
                                                    </div> <!-- end media-body-->
                                                </div>
                                            </div> <!-- end col--> 
                                           <div class="col-sm-4">
                                                <div class="text-center mt-sm-0 mt-3 text-sm-right">
                                                    <button type="button" class="btn btn-light" onclick="Reset_key();" style="font-size: 6px;">
                                                        <i class="fa fa-refresh"></i>重置对接KEY
                                                    </button>
                                                    <a href="Api_Set.php" class="btn btn-light" style="font-size: 6px;">
                                                        查看对接KEY
                                                    </a>
                                                </div>
                                            </div> <!-- end col-->											
                                        </div> <!-- end row -->
                                    </div> <!-- end card-body/ profile-user-box-->
                                </div><!--end profile/ card -->
                            </div> <!-- end col-->
                        </div>
		<div class="row"> 
	  <div class="col-md-18 col-lg-18 col-xl-6">
                                 <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-2">平台公告</h4>
                                        <!--div class="slimscroll" style="max-height: 275px;"-->
											<div class="timeline-alt pb-0">
                                               <?php 
    $rs = $DB->query("SELECT * FROM pay_notice where status='1' order by id ASC limit 99999");
    while ($res = $rs->fetch()) {
        echo '
		<div class="timeline-item">
        <div class="timeline-item-info">
        <i class="iconfont icon-tixing" style="font-size: 20px;color: '.($res['color']?$res['color']:null).'"></i>
        <a href="#">'.$res['title'].'</a></i>
        <small><font color="'.($res['color']?$res['color']:null).'">'.$res['datatxt'].'</font></small>
         <p class="mb-0 pb-2">
         <small class="text-muted">'.$res['addtime'].'</small></p>
         </div>
         </div>';
	}
    ?>
        <div>
        </div>
                                            </div>
                                            <!-- end timeline -->
                                        </div> <!-- end slimscroll -->
                                    </div>
                               <div class="card bg-white m-b-30">
          <span class="mb-0 pb-2" style="font-size: 6px;color:">
											<div class="col-12-4 col-md-12 col-sm-12 col-xs-12 bk-bg-white bk-padding-top-10">
												<i class="fa fa-tablet"></i> QQ号+<?=$conf['qq']?>
											</div>
											<div class="col-12-4 col-md-12 col-sm-12 col-xs-12 bk-bg-white bk-padding-top-10">
												<i class="fa fa-tablet"></i> QQ群+<?=$conf['qq_qun']?>
											</div>
											<div class="col-12-4 col-md-12 col-sm-12 col-xs-12 bk-bg-white bk-padding-top-10">
												<i class="fa fa-envelope"></i> <?=$conf['qq']?>@qq.com</span>
											</div>
        </div>
         </div>
                                <!-- Column -->
                                <div class="col-md-8 col-lg-8 col-xl-3">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div class="col-3 align-self-center">
                                                    <div class="round">
                                                        <i class="mdi mdi-webcam"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6 align-self-center text-center">
                                                    <div class="m-l-10">
                                                        <h5 class="mt-0 round-inner"><?php echo $userrow['money']?>￥</h5>
                                                        <h5 class="mb-0 text-muted">可用额度</h5>                                                                 
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            </div>
                                <!-- Column -->
                               <!-- Column -->
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div class="col-3 align-self-center">
                                                    <div class="round">
                                                        <i class="mdi mdi-rocket"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6 align-self-center text-center">
                                                    <div class="m-l-10">
                                                        <h5 class="mt-0 round-inner"><?php echo $count5?$count5:0?>￥</h5>
                                                        <h5 class="mb-0 text-muted">完成金额</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        </div>  
                                        </div>       
                                <div class="col-md-8 col-lg-8 col-xl-3">
                                <!-- Column -->
                                <!-- Column -->
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div class="col-3 align-self-center">
                                                    <div class="round">
                                                        <i class="mdi mdi-account-multiple-plus"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6 text-center align-self-center">
                                                    <div class="m-l-10 ">
                                                        <h5 class="mt-0 round-inner"><?php echo $count2?>笔</h5>
                                                        <h5 class="mb-0 text-muted">成功订单</h5>
                                                    </div>
                                                </div>                                          
                                            </div>
                                        </div>
                                    </div>
                                <!-- Column -->
                                <!-- Column -->
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div class="col-3 align-self-center">
                                                    <div class="round ">
                                                        <i class="mdi mdi-basket"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6 align-self-center text-center">
                                                    <div class="m-l-10 ">
                                                        <h5 class="mt-0 round-inner"><?php echo $count1?>笔</h5>
                                                        <h5 class="mb-0 text-muted">订单总数</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                            </div>                                                                                                                                           
                            <div class="row">
							
                                <div class="col-md-12 col-lg-12 col-xl-12 align-self-center">
                                    <div class="card bg-white m-b-30">
                                        <div class="card-body new-user">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-top-0" style="width:60px;">#</th>
                                                            <th class="border-top-0">#</th>
                                                            <th class="border-top-0">今</th>
                                                            <th class="border-top-0">昨</th>                                    
                                                            <th class="border-top-0">同比</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <img class="rounded-circle" src="/Core/Assets/Img/alipay.jpeg" alt="user" width="40"> </td>
                                                            <td>
                                                                <a href="javascript:void(0);">支付宝</a>
                                                            </td>
                                                            <td><?php echo round($data['order_today']['alipay'],2)?></td>
                                                            <td><?php echo round($data['order_lastday']['alipay'],2)?></td>  
                                                            <td><?php $tuday=round($data['order_today']['alipay'],2);
                                                                      $yesterday=round($data['order_lastday']['alipay'],2);
																	  $tb1 = $tb1?$tb1:1; 
																	  $yesterday = $yesterday?$yesterday:1;  
                                                                      $tb1=round(($tuday-$yesterday)); 
                                                                      $tb2=round($tb1/$yesterday*100,2);
                                                                      echo round($tb2) ?>%</td>   
                                                           
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                 <img class="rounded-circle" src="/Core/Assets/Img/weixin.jpeg" alt="user" width="40"> </td>
                                                            <td>
                                                                <a href="javascript:void(0);">微信</a>
                                                            </td>
                                                            <td><?php echo round($data['order_today']['wxpay'],2)?></td>
                                                            <td><?php echo round($data['order_lastday']['wxpay'],2)?></td>                                   
                                                            <td><?php $tuday=round($data['order_today']['wxpay'],2);
                                                                      $yesterday=round($data['order_lastday']['wxpay'],2);
																	  $tb1 = $tb1?$tb1:1; 
																	  $yesterday = $yesterday?$yesterday:1;  
                                                                      $tb1=round($tuday-$yesterday,2); 
                                                                      $tb2=round($tb1/$yesterday*100,2);
                                                                      echo round($tb2,2) ?>%</td> 
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                               <img class="rounded-circle" src="/Core/Assets/Img/qq.jpeg" alt="user" width="40"> </td>
                                                            <td>
                                                                <a href="javascript:void(0);">Q钱包</a>
                                                            </td>
                                                            <td><?php echo round($data['order_today']['qqpay'],2)?></td>
                                                            <td><?php echo round($data['order_lastday']['qqpay'],2)?></td>                                   
                                                            <td><?php $tuday=round($data['order_today']['qqpay'],2);
                                                                      $yesterday=round($data['order_lastday']['qqpay'],2);
																	  $tb1 = $tb1?$tb1:1; 
																	  $yesterday = $yesterday?$yesterday:1;  
                                                                      $tb1=round($tuday-$yesterday,2); 
                                                                      $tb2=round($tb1/$yesterday*100,2);
                                                                       echo round($tb2,2) ?>%</td> 
                                                       
                                                        </tr>
                                                        <tr >
                                                            <td>
                                                               <img class="rounded-circle" src="/Core/Assets/Img/total.jpg" alt="user" width="40"> </td>
                                                            <td>
                                                                <a href="javascript:void(0);">总计</a>
                                                            </td>                                                
                                                            <td><?php echo round($data['order_today']['all'],2)?></td>
                                                            <td><?php echo round($data['order_lastday']['all'],2)?></td>                                   
                                                            <td><?php $tuday=round($data['order_today']['all'],2);
                                                                      $yesterday=round($data['order_lastday']['all'],2);
																	  $tb1 = $tb1?$tb1:1; 
																	  $yesterday = $yesterday?$yesterday:1;  
                                                                      $tb1=round($tuday-$yesterday,2); 
                                                                      $tb2=round($tb1/$yesterday*100,2);
                                                                       echo round($tb2,2) ?>%</td> 
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
		  
<script>
function Reset_key(){//POST提交
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Reset_key",
				//data : {},
				dataType : 'json',
				timeout:10000,
				success : function(data) {					  
					  layer.close(ii);
					  layer.msg(data.msg);
					  if(data.code==1){
						setTimeout(function () {
							location.href="?";
						}, 1000); //延时1秒跳转
					  }
				},
				error:function(data){
					layer.close(ii);
					layer.msg('服务器错误');
					}
			});
}
  </script>
          <?php include'Foot.php';?>
	<script>
	<?php if(!empty($conf['modal'])){?>
	$('#myModal').modal('show');
	<?php }?>
  </script>
</html>