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
 * 支付设置
**/
$title='支付设置';
include './Head.php';

if($_GET['WIDout_trade_no'] and $_GET['WIDtotal_fee'] and $_GET['WIDsubject']){
	exit("<script language='javascript'>window.location.href='./SDK/epayapi.php?WIDout_trade_no=".$_GET['WIDout_trade_no']."&WIDsubject=".$_GET['WIDsubject']."&WIDtotal_fee=".$_GET['WIDtotal_fee']."&type=".$_GET['type']."';</script>");
}

$mblist = \lib\PayTemplate::getList();
?>
<!-- APP MAIN ==========-->
		<div class="row"> 
	  <div class="col-md-12 col-lg-12 col-xl-6">
                               <div class="card bg-white m-b-30">
                                        <div class="card-body new-user">
                                            <h5 class="header-title mb-4 mt-0"> 支付设置</h4>
					<div class="panel-body">
							<div class="form-group">
                                <label>订单超时时间(秒):</label>
                                <input type="text" id="outtime" name="outtime" value="<?=$userrow['outtime']?>" class="form-control" ><font color="green">如不设置默认"<?php echo $conf['outtime'];?>"秒,5分钟超时请填写"300"</font>
                            </div>  
							<h4>当前支付页面使用模板：</h4>
  <div class="row text-center">
	  <div class="col-xs-6 col-sm-4">
		<img class="img-responsive img-thumbnail img-rounded" src="/Submit/Template/<?php echo $userrow['pay_template']?>/preview.png" onerror="this.src='/Submit/Template/<?php echo $userrow['pay_template']?>/preview.png'">
	  </div>
	  <div class="col-xs-6 col-sm-4">
		<p>模板名称：<?php echo $userrow['pay_template']?></p>
	  </div>
  </div>
  <hr/>
  <h4>更换模板：</h4>
  <div class="row text-center">
  <?php foreach($mblist as $pay_template){?>
	  <div class="col-xs-2 col-sm-3 mblist">
		<a href="javascript:Change_pay_Template('<?php echo $pay_template?>')"><img class="img-responsive img-thumbnail img-rounded" src="/Submit/Template/<?php echo $pay_template?>/preview.png" onerror="this.src='/Submit/Template/<?php echo $pay_template?>/preview.png'" title="点击更换到该模板"><br/><?php echo $pay_template?></a>
	  </div>
  <?php }?>
  </div>
							<!--div class="form-group">
                                <label>对接PID</label>
                                <input type="text" id="pid" name="pid" value="<?=$userrow['pid']?>" class="form-control" >
                            </div>
							<div class="form-group">
                                <label>对接KEY</label>
                                <input type="text" id="key" name="key" value="<?=$userrow['key']?>" class="form-control" ><font color="green">需要重置KEY请在用户中心那重置哦</font>
                            </div-->
        </div>
      </div>
      </div>
      </div>
						
	  <div class="col-md-12 col-lg-12 col-xl-6">
                               <div class="card bg-white m-b-30">
                                        <div class="card-body new-user">
                                            <h5 class="header-title mb-4 mt-0">支付掉线对接配置</h4>
					<div class="panel-body">
							<div class="form-group">
                                <label>支付宝收款模式</label>
                                <select class="form-control" id="alipay_pay_open" name="alipay_pay_open" default="<?php echo $userrow['alipay_pay_open']?>" onclick="setALIPAY(this);">
								<?php if($userrow['alipay_pay_open']==0){?>
									<option value="0">[模式①]掉线不可收款</option>
									<option value="1">[模式②]掉线可继续收款</option>
									<option value="2">[模式③]掉线临时对接其他易支付</option>
								<?php }elseif($userrow['alipay_pay_open']==1){?>
									<option value="1">[模式②]掉线可继续收款</option>
									<option value="0">[模式①]掉线不可收款</option>
									<option value="2">[模式③]掉线临时对接其他易支付</option>
								<?php }else{?>
									<option value="2">[模式③]掉线临时对接其他易支付</option>
									<option value="0">[模式①]掉线不可收款</option>
									<option value="1">[模式②]掉线可继续收款</option>
								<?php }?>
								</select>
                            </div>
							<div id="setALIPAY" style="<?php echo $userrow['alipay_pay_open']==2?null:'display:none;'; ?>">
							<div class="form-group">
                                <label>其他易支付URL</label>
                                <input type="text" id="alipay_api_url" name="alipay_api_url" value="<?=$userrow['alipay_api_url']?>" class="form-control" >
                            </div>
							<div class="form-group">
                                <label>其他易支付PID</label>
                                <input type="text" id="alipay_api_pid" name="alipay_api_pid" value="<?=$userrow['alipay_api_pid']?>" class="form-control" >
                            </div>
							<div class="form-group">
                                <label>其他易支付KEY</label>
                                <input type="text" id="alipay_api_key" name="alipay_api_key" value="<?=$userrow['alipay_api_key']?>" class="form-control" >
                            </div>
                            </div>
							</div>
							<div class="form-group">
                                <label>QQ钱包收款模式</label>
                                <select class="form-control" id="qqpay_pay_open" name="qqpay_pay_open" default="<?php echo $conf['qqpay_pay_open']?>" onclick="setQQPAY(this);">
								<?php if($userrow['qqpay_pay_open']==0){?>
									<option value="0">[模式①]掉线不可收款</option>
									<option value="1">[模式②]掉线可继续收款</option>
									<option value="2">[模式③]掉线临时对接其他易支付</option>
								<?php }elseif($userrow['qqpay_pay_open']==1){?>
									<option value="1">[模式②]掉线可继续收款</option>
									<option value="0">[模式①]掉线不可收款</option>
									<option value="2">[模式③]掉线临时对接其他易支付</option>
								<?php }else{?>
									<option value="2">[模式③]掉线临时对接其他易支付</option>
									<option value="0">[模式①]掉线不可收款</option>
									<option value="1">[模式②]掉线可继续收款</option>
								<?php }?>
								</select>
                            </div>
							<div id="setQQPAY" style="<?php echo $userrow['qqpay_pay_open']==2?null:'display:none;'; ?>">
							<div class="form-group">
                                <label>其他易支付URL</label>
                                <input type="text" id="qqpay_api_url" name="qqpay_api_url" value="<?=$userrow['qqpay_api_url']?>" class="form-control" >
                            </div>
							<div class="form-group">
                                <label>其他易支付PID</label>
                                <input type="text" id="qqpay_api_pid" name="qqpay_api_pid" value="<?=$userrow['qqpay_api_pid']?>" class="form-control" >
                            </div>
							<div class="form-group">
                                <label>其他易支付KEY</label>
                                <input type="text" id="qqpay_api_key" name="qqpay_api_key" value="<?=$userrow['qqpay_api_key']?>" class="form-control" >
                            </div>
                            </div>
					</div>
        </div>
         </div>
	  <div class="col-md-12 col-lg-12 col-xl-12">
                               <div class="card bg-white m-b-30">
                                        <div class="card-body new-user">
					<div class="panel-body">
							<div class="form-group">
								<button type="sumbit" class="btn btn-primary btn-block" onclick="pay_set();">确定修改</button>
         </div> 
		</div><!-- .row -->
        </div>
         </div>
         </div> 
  <script type="text/javascript">
function setALIPAY(){
    var alipay_pay_open= $("#alipay_pay_open").val(); 
	if(alipay_pay_open == 2){
        var user= $("#user").val(); 
		$("#setALIPAY").show();
	}else{
		$("#setALIPAY").hide();
	}
}
function setQQPAY(){
    var qqpay_pay_open= $("#qqpay_pay_open").val(); 
	if(qqpay_pay_open == 2){
        var user= $("#user").val(); 
		$("#setQQPAY").show();
	}else{
		$("#setQQPAY").hide();
	}
}
function pay_set(){//POST提交
        var outtime= $("#outtime").val(); 
        var alipay_pay_open= $("#alipay_pay_open").val(); 
        var alipay_api_url= $("#alipay_api_url").val(); 
        var alipay_api_pid= $("#alipay_api_pid").val(); 
        var alipay_api_key= $("#alipay_api_key").val(); 
        var qqpay_pay_open= $("#qqpay_pay_open").val(); 
        var qqpay_api_url= $("#qqpay_api_url").val(); 
        var qqpay_api_pid= $("#qqpay_api_pid").val(); 
        var qqpay_api_key= $("#qqpay_api_key").val(); 
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Pay_set",
				data : {outtime,alipay_pay_open,alipay_api_url,alipay_api_pid,alipay_api_key,qqpay_pay_open,qqpay_api_url,qqpay_api_pid,qqpay_api_key},
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

function Change_pay_Template(pay_template){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'Ajax.php?act=Change_pay_Template',
		data : {pay_template:pay_template},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 1){
				layer.alert('更换支付模板成功！', {
					icon: 1,
					closeBtn: false
				}, function(){
				  window.location.reload()
				});
			}else{
				layer.alert(data.msg, {icon: 2})
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
	return false;
}
  </script>
<?php
	include './Foot.php';
?>