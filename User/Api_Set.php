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
	  <div class="col-md-12 col-lg-12 col-xl-6">
                               <div class="card bg-white m-b-30">
                                        <div class="card-body new-user">
                                            <h5 class="header-title mb-4 mt-0"> 对接Api</h4>
					<div class="panel-body">
							<div class="form-group">
                                <label>对接接口</label>
                                <input type="text" id="apiurl" name="apiurl" value="http://<?=$_SERVER['HTTP_HOST']?>/" class="form-control" disabled>
                            </div>
							<div class="form-group">
                                <label>对接PID</label>
                                <input type="text" id="pid" name="pid" value="<?=$userrow['pid']?>" class="form-control" disabled>
                            </div>
							<div class="form-group">
                                <label>对接KEY</label>
                                <input type="text" id="key" name="key" value="<?=$userrow['key']?>" class="form-control" disabled><font color="green">需要重置KEY请在用户中心那重置哦</font>
                            </div>
				<div class="form-group"><a href="./doc.php" class="btn btn-sm btn-success" target="_blank">查看开发文档</a>
				 </div>
        </div>
      </div>
      </div>
      </div>
						
	  <div class="col-md-12 col-lg-12 col-xl-6">
                               <div class="card bg-white m-b-30">
                                        <div class="card-body new-user">
                                            <h5 class="header-title mb-4 mt-0">修改商户信息</h4>
					<div class="panel-body">
							<div class="form-group">
                                <label>自定义登录账号</label>
                                <input type="text" id="user" name="user" value="<?=$userrow['user']?>" class="form-control">
                            </div>
							<div class="form-group">
                                <label>自定义登录密码</label>
                                <input type="text" id="pass" name="pass" value="<?=$userrow['pass']?>" class="form-control">
                            </div>
							<div class="form-group">
                                <label>绑定QQ号</label>
                                <input type="text" id="qq"  name="qq" value="<?=$userrow['qq']?>" class="form-control">
					</div>
							<div class="form-group">
								<button type="sumbit" class="btn btn-primary btn-block" onclick="set();">确定修改</button>
							</div>
        </div>
         </div>
         </div>
         </div>           
         </div>
		</div><!-- .row -->
  <script type="text/javascript">
		
function set(){//POST提交
        var user= $("#user").val(); 
        var pass= $("#pass").val(); 
        var qq= $("#qq").val(); 
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Set",
				data : {user,pass,qq},
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
<?php
	include './Foot.php';
?>