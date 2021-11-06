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

require_once('../Core/Common.php');
$trade_no=daddslashes($_GET['trade_no']);
$sitename=base64_decode(daddslashes($_GET['sitename']));
$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$trade_no}' limit 1")->fetch();
if(!$srow)sysmsg('该订单号不存在，请返回来源地重新发起请求！');
$QR_row=$DB->query("SELECT * FROM pay_qrlist WHERE id='{$srow['qr_id']}' limit 1")->fetch();

$userrow=$DB->query("SELECT * FROM pay_user WHERE pid='{$srow['pid']}' limit 1")->fetch();
$outtime         = ($srow['outtime']-time());
$price			 = ($srow['price']>=0.01)?daddslashes($srow['price']):daddslashes('请稍等...');
$type			 = $_GET['type']?daddslashes($_GET['type']):daddslashes($srow['type']);

if($type == 'wxpay'){
	$typeName = '微信';
}elseif($type == 'qqpay'){
	$typeName = 'QQ钱包';
}else{
	$typeName = '支付宝';
}
$alipayh5url_1 = "https://render.alipay.com/p/s/i?scheme=".urlencode("alipays://platformapi/startapp?saId=10000007&qrcode=".urlencode($siteurl."Ali_PayH5.php?trade_no=".$trade_no));
$alipayh5url_2 = "https://render.alipay.com/p/s/i?scheme=".urlencode("alipays://platformapi/startapp?saId=10000007&qrcode=".urlencode($siteurl."Ali_PayH5_Qr.php?trade_no=".$trade_no));
?>
	<script>
	if(Is_Wx_Al()=='alipay'){
		//location.href = "https://qr.alipay.com/fkx04746lf1mfjudyx32619";
		//location.href = "<?=$pay_url?>";
	}else if(Is_Wx_Al()=='alipay'){
		
	}
	
	
	
function Is_Wx_Al(){
	//window.navigator.userAgent 属性包含了浏览器类型、版本、操作系统类型、浏览器引擎类型等信息，这个属性可以用来判断浏览器类型
	var ua = window.navigator.userAgent;
	//通过正则表达式匹配ua中是否含有MicroMessenger AlipayClient字符串
	if (/MicroMessenger/.test(ua)) {
			return 'wxpay';
		} else if (/AlipayClient/.test(ua)) {
            return 'alipay';
        } else {
            return '其他浏览器';
    }     
}
	 	 //如果3秒后不出二维码就跳转刷新一次,防缓存
    if( !$.cookie("<?=$trade_no?>") && updateQrImg == 0){
		setTimeout(function(){location.href='Mcode_Pay.php?trade_no=<?=$trade_no?>&sitename=<?=$sitename?>';},3000); 
		var cookietime = new Date(); 
		cookietime.setTime(cookietime.getTime() + (10*60*1000));
		$.cookie("<?=$trade_no?>", false, { expires: cookietime });
	} 
</script>
<style>
body{background:#f2f2f4;}
body,html{width:100%;height:100%;}
*,:after,:before{box-sizing:border-box;}
*{margin:0;padding:0;}
img{max-width:100%;}
#header{height:60px;border-bottom:2px solid #eee;background-color:#fff;text-align:center;line-height:60px;}
#header h1{font-size:20px;}
#main{overflow:hidden;margin:0 auto;padding:20px;padding-top:80px;width:992px;max-width:100%;}
#main .left{float:left;width:40%;box-shadow:0 0 60px #b5f1ff;}
.left p{margin:10px auto;}
.make{padding-top:15px;border-radius:10px;background-color:#fff;box-shadow:0 3px 3px 0 rgba(0,0,0,.05);color:#666;text-align:center;transition:all .2s linear;}
.make .qrcode{margin:auto;}
.make .money{margin-bottom:0;color:#f44336;font-weight:600;font-size:18px;}
.info{padding:15px;width:100%;border-radius:0 0 10px 10px;background:#32343d;color:#f2f2f2;text-align:center;font-size:14px;}
#main .right{float:right;padding-top:25px;width:60%;color:#ccc;text-align:center;}
@media (max-width:768px){
#main{padding-top:30px;}
#main .left{width:100%;}
#main .right{display:none;}
}
</style>
        <link rel="stylesheet" type="text/css" href="<?php echo PAYSTATIC_ROOT?>css/qrcode.css">
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>在线支付 - <?php echo $typeName ?> - 网上支付 安全快速！</title>
    <link rel="stylesheet" type="text/css" href="<?php echo PAYSTATIC_ROOT?>css/main.css">
    <script src="<?php echo PAYSTATIC_ROOT?>js/jquery.min.js"></script>
	<script src="<?php echo PAYSTATIC_ROOT?>js/qrcode.min.js"></script>
</head>
<body>
<div class="main">
    <div class="w">
          <div class="order clearfix order-init order-init-oldUser-noQrcode">
    <div class="o-left"><h3 class="o-title">订单提交成功，请尽快付款！订单号：<?=$trade_no?></h3>
        <p class="o-tips">             
        </p>
    </div>
    <div class="o-right">
        <div class="o-price"><em>商品名称：</em><?=$srow['name']?> | <em>应付金额</em><strong id="price"><?=$price?></strong><em>元</em></div>
            </div>
    <div class="o-list j_orderList" id="listPayOrderInfo">

    </div>
</div>

        <div class="payment">
            <div class="pay-wxpay">
                <div class="p-w-hd"><?=$typeName?>支付</div>
                <div class="p-w-bd" style="position:relative">
                    <div class="j_weixinInfo font-red" style="position:absolute; top: -36px;font-size: 15px;left: 130px;">付款成功后,等10秒页面会自动跳转,勿关闭此页面</div>
                    <div class="p-w-box">
                        <div class="pw-box-hd">
                            <div class="qrcode" id="qrcode"><img id="qrcode_load" src="<?php echo PAYSTATIC_ROOT?>img/loading.gif" style="display: block;"></div>                        <!--img id="weixinImageUrl" src="" width="298" height="298"-->
                        </div>
       <center>
					<a id="alipayh5url_1"></a>
					<a id="alipayh5url_2"></a>
				<?php if($userrow[$type.'_pay_open']==1 and $QR_row['status']!=1)echo '<p style="color: red;font-size: 17px;word-break: keep-all;">商家账号状态未在线</p>';?>
		</center>    
                        <div class="pw-box-ft-wxpay">
                            <p>请使用<?=$typeName?>支付扫一扫</p>
                            <p>扫描二维码支付</p>
                        </div>
                        <!--p style="margin-top:8px">如果发现商家商品违规或者被骗请举报订单<a href="javascript:;" onclick="Report_prompt();"> 点此举报s</a></p-->                    </div>
                    <div class="p-w-sidebar-wxpay"></div>
                </div>
            </div>
            

        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo PAYSTATIC_ROOT?>layer/layer.js"></script>
     <script type="text/javascript">
       var intDiff = parseInt('<?=$outtime?>');//倒计时总秒数量
     function timer(intDiff){
         window.setInterval(function(){
         var day=0,
             hour=0,
             minute=0,
             second=0;//时间默认值       
         if(intDiff > 0){
         	day = Math.floor(intDiff / (60 * 60 * 24));
             hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
             minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
         }
		if (minute <= 9) minute = '0' + minute;
		if (second <= 9) second = '0' + second;
		if (hour <= 0 && minute <= 0 && second <= 0) {
			$("#divTime").html("<small style='color:red; font-size:26px'>订单二维码已过期</small>");
			$("#qrcode").html('<img id="qrcode_load" src="<?php echo PAYSTATIC_ROOT?>img/qrcode_timeout.png">');//输出过期二维码提示图片
		}else{
			$("#divTime").html("二维码有效时间:<small style='color:red; font-size:26px'>" + minute + "</small>分<small style='color:red; font-size:26px'>" + second + "</small>秒,失效勿付");
		}
		intDiff--
		}, 1000);
     } 
	 
     $(function(){
         timer(intDiff);
     });

    
	order();
	updateQrOk = 0;
	updateQrImg= 0;
	updateQrNo = 0;
     //订单监控  {订单监控}
	function order(){
        $.get("Mcode_Get.php",{trade_no: "<?php echo $trade_no?>"},function(result){
			//成功
     		if(result.code == '200' && updateQrOk==0){
				updateQrOk==1;
				$("#divTime").html("<small style='color:red; font-size:22px'>"+ result.msg +"</small>");
				//$("#divMsg_a").html(result.msg);//输出提醒1
				//$("#divMsg_b").html(result.msg);//输出提醒2
				$("#qrcode").html('<img id="qrcode_load" src="<?php echo PAYSTATIC_ROOT?>img/pay_ok.png">');//输出过期二维码提示图片
 				//回调页面
         		window.clearInterval(orderlst);
				layer.msg('支付成功，正在跳转中...');
				window.location.href=result.data.backurl;
     		}
     		//支付二维码
     		if(result.code == '100' && updateQrImg==0){
 				updateQrImg = 1;
				var pay_type = '<?=$type?>';//支付方式
				var is_money = <?=$srow['money']?>;
				if(is_money!=result.data.money){
					layer.alert('温馨提示:'+is_money+'金额已被其他用户金额占用,请您务必付款<font color=red>'+result.data.money+'</font>元,<font color=red>多付一分或者少付一分都不能到账</font>!', {
					icon: 1,
					skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
					});
				}
			
				//$("#divMsg_a").html("禁止使用花呗付款,否则不能自动到账!");//输出提醒1
				//$("#divMsg_b").html("遇无法支付的二维码,请返回重新充值!");//输出提醒2
				$('#price').html('¥'+result.data.money+'<font color=red style="font-size:8px">(不可多付或少付)</font>');//输出真实付款金额
				$("#qrcode").html('<img id="qrcode_load" src="'+ result.data.qrcode +'">');//输出过期二维码提示图片
				
				if(pay_type == "alipay"){
					if(window.navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i)){
						$("#alipayh5url_1").html('<small> <font color=red style="font-size:16px">如果无法跳转请扫码</font></div></small> <a type="button" href="<?=$alipayh5url_1?>" class="btn btn-lg btn-block btn-default" style="font-size:13px" target="_blank">唤醒支付宝APP支付[免输入金额]</a>');//H5按钮1
						$("#alipayh5url_2").html('<a type="button" href="<?=$alipayh5url_2?>" class="btn btn-lg btn-block btn-success" style="font-size:13px" target="_blank">唤醒支付宝APP支付[需输入金额]</a>');//H5按钮2
					}
				}
				
 				//设置参数方式 
				/*
				$('#qrcode_load').remove();//隐藏等待图片
 				var qrcode = new QRCode('qrcode', {
 				  text: result.data.qrcode, 
 				  width: 256, 
 				  height: 256, 
 				  colorDark : '#000000', 
 				  colorLight : '#ffffff', 
 				  correctLevel : QRCode.CorrectLevel.H 
 				});
				*/
			}
         	//订单已经超时
     		if(result.code == '-1' && updateQrNo==0){
				updateQrNo==1;
				$("#divTime").html("<small style='color:red; font-size:22px'>"+ result.msg +"</small>");
				//$("#divMsg_a").html(result.msg);//输出提醒1
				//$("#divMsg_b").html(result.msg);//输出提醒2
     			window.clearInterval(orderlst);
     			layer.confirm(result.msg, {
     			  icon: 2,
     			  title: '支付失败',
   				  btn: ['确认'] //按钮
   				}, function(){
					location.href=result.data.backurl
   				});
         	}
			
     	},"JSON");
	}
	//周期监听 
	orderlst = window.setInterval(function () {
		order();
	}, 2000); 
	/*
function Report_prompt(){
    //prompt层
    layer.prompt({title: '请输入违规内容', formType: 2, btn: ['提交','取消']}, function(text, index){
        layer.close(index);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/api.php?act=order_report",
            timeout: 10000, //ajax请求超时时间10s
            data: {pid: "1000", text: text, out_trade_no: "20210508135729623", product_name: "测试商品"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                layer.msg(data.msg);
            },
        });
    });
};
*/
</script>
	
<div class="p-footer">
    <div class="pf-wrap w">
        <div class="pf-line">
            <span class="pf-l-copyright"></span>
        </div>
    </div>
</div>    
</body>
</html>
<script>
