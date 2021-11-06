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

$title='支付云端设置';
include './Head.php';
?>
<?php
	// foreach ($Instant_Url_List as $key => $val) {
	// 		$checkServerSatatus = checkServerSatatus($Instant_Url_List[$key]);
	// 		$select.= '<option value="'.$Instant_Url_List[$key].'">【'.($key+1).'号】['.$checkServerSatatus['Country'].'] (ms:'.$checkServerSatatus['Ping'].')</option>';
	// }
	// if(!$select)$select = '<option value="/">0_无服务器(ms:9999)</option>';
	$select = '<option value="localhost">【1号】本地服务器去云端(ms:0)</option>';
?>
<!-- APP MAIN ==========-->
<div class="row">
			<div class="col-sm-5 col-md-5">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title"><span class="glyphicon glyphicon-globe"></span> 官方温馨提示</h4>
					</div>
					<div class="panel-body">
							<div class="form-group">
                                <label>PS：</label>
								<font color="red"><br>
								1.云端账号密码禁止泄露与他人共用<br>
								2.同一个服务器禁止搭建两个本系统<br>
								3.我们系统提供多个分布式云端,可自行选择一个<br>
								4.如果您不遵守一、二,我们有权取消您继续使用本系统权限!<br>
								<br>
								系统每次更新,每个人更新的数据都有不一样的特征码,如果您把您的信息与他人共享,系统一旦检测到,将取消您继续使用本系统的权限
								
								</font>
					</div>
							<!--div class="form-group">
						<button type="sumbit" class="btn btn-primary btn-block" onclick="paymin_set();">确定修改</button>
								
							</div-->
				</div>
			</div>
			</div>
			
			<div class="col-sm-7 col-md-7">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title"><span class="glyphicon glyphicon-globe"></span> 云端配置</h4>
					</div>
					<div class="panel-body">
				<table class="table table-bordered table-striped">
		    <thead><tr><th class="danger">服务器/云端_“PID”</th><th class="danger">订单超时(秒)/算法_“Key”</th></thead>
            <tbody>
			  <tr><td><div class="col-sm-10"><select class="form-control" id="Instant_url" name="Instant_url" default="localhost"><?=$select?></option></select>
			  </td><td><div class="col-sm-10"><input type="text" id="outtime" name="outtime" value="0" class="form-control" placeholder=""/>
			  
			  </td></tr>
			  </td><td><div class="col-sm-10"><input type="text" id="Instant_pid" name="Instant_pid" value="localhost" readonly class="form-control" placeholder=""/>
			  </td><td><div class="col-sm-10"><input type="text" id="Instant_key" name="Instant_key" value="localhost" readonly class="form-control" placeholder=""/>
			  </td></tr>
			  <?php $Query_row = $Instant_Api->Query();?>
			  </td><td><div class="col-sm-10"><input type="text" id="Instant_money" name="Instant_money" value="当前额度：99999999999" class="form-control" disabled>
			  </td><td><div class="col-sm-10"><a class="form-control"><span class="glyphicon glyphicon-globe"></span>状态正常</a>
			  </td></tr>
			</tbody>
          </table>
							<div class="form-group">
						<button type="sumbit" class="btn btn-primary btn-block" onclick="paytype_set();">确定修改</button>
							</div>
					</div>
					</div><!-- .row --> 
	<script type="text/javascript">
function paytype_set(){//支付通道配置
        var Instant_url = 'localhost';  
        var outtime = 1;  
        var Instant_pid= 'localhost';
        var Instant_key= 'localhost';
		// if(Instant_url=='' || outtime=='' || Instant_pid=='' || Instant_key==''){layer.alert('请确保各项不能为空！');return false;}
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Pay_set",
				data : {Instant_url,outtime,Instant_pid,Instant_key},
				dataType : 'json',
				timeout:10000,
				success : function(data) {					  
					  layer.close(ii);
					  layer.msg(data.msg);
					  if(data.code==1){
						setTimeout(function () {
							location.href="./Pay.php";
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
