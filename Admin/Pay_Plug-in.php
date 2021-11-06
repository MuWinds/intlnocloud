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
								1.功能已破解<br>
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
						<h4 class="panel-title"><span class="glyphicon glyphicon-globe"></span> 其他增值插件授权</h4>
					</div>
					<div class="panel-body">
                     <label>当前云端支付额度(<font color="red">999999999</font>)</label>
					<div class="input-group">
					<input type="text" name="Instant_money" id="Instant_money" value="无限制" class="form-control no-border" disabled>
					<a class="input-group-addon" id="sendcode" href="javascript:;" onclick="alert('功能已破解，已解除限制')">点此购买10000额度(¥ 0)</a>
			</div>	
					<pre><font color="green">PS：若没有额度,提交不了支付订单,若当前额度只有100,那将发起不了100以上金额的订单哦</font></pre>
			</br>	
                     <label>支付宝账号密码登陆(带自动更新)授权到期时间：</label>
					<div class="input-group">
					<input type="text" name="Instant_money" id="Instant_alipay_up_outtime" value="有效期至：<?=date('Y-m-d H:i:s',strtotime("+100year"))?>" class="form-control no-border" disabled>
					<a class="input-group-addon" id="sendcode" href="javascript:;" onclick="alert('功能已破解，已解除限制')">购买一个月(¥ 0)</a>
			</div>	
					<pre><font color="green">PS：此插件授权,全站商户支持支付宝账号密码登陆,并支持自动更新支付宝CK(暂未开通)</font></pre>
			</div>
			</div>	
					</div><!-- .row --> 