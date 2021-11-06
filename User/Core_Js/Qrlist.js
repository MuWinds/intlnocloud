function Del_Qr(id) { //删除二维吗
	var confirmobj = layer.confirm('此操作将会删除此数据，是否确定？', {
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

$(document).ready(function() {
	$('.picurl > input').bind('focus mouseover',
	function() {
		if (this.value) {
			this.select()
		}
	});
	$("input[type='file']").change(function(e) {
		$('#qr_url').val('解码中');
		Upload(this.files)
	});
});

function Upload() {
	var ii = layer.load(3, {
		shade: [0.1, '#fff']
	});
	var file = document.getElementById("imgfile").files[0];
	var formData = new FormData();
	formData.append('image_field', file);
	$.ajax({
		url: "Ajax.php?act=Add_Qrcode",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		mimeType: "multipart/form-data",
		dataType: 'json',
		success: function(data) {
			/*layer.close(ii);
									$('#qr_url').val(data.qr_url);	  
							*/
			if (data.code == 1) {

				layer.close(ii);
				$('#qr_url').val(data.qrcode);
				var type = $("#type").val();
				if (type == 'wxpay') {
					$("#beizhu_name").html('微信昵称'); //输出提示
					$("#beizhu").val(''); //输出提示
					$("#LoginQrcode_msg").html('以上必须填写即将登陆微信的完整[微信昵称],不要带花里胡哨的昵称,否则无法成功登陆上'); //输出提示
					$("#Wx_Sumbit").html('</br><button type="sumbit" class="btn btn-primary btn-block" onclick="GET_wx_QR();">确认以上并添加</button>');
				} else if (data.qrcode) {
					$("#Wx_Sumbit").html('</br><button type="sumbit" class="btn btn-primary btn-block" onclick="Add_Qr();">确认以上并添加</button>');
				} else {
					layer.msg('添加或解二维码失败');
				}
			} else {
				layer.close(ii);
				layer.msg(data.msg);
				setTimeout(function() {
					location.href = "?";
				},
				3000); //延时1秒跳转
			}
		},
		error: function(data) {
			layer.close(ii);
			layer.msg('请剪切边框或更换其他二维码重试');
			setTimeout(function() {
				location.href = "?";
			},
			3000); //延时1秒跳转
		}
	});
}

//添加二维码逻辑
function GET_wx_QR() {
	var beizhu = $("#beizhu").val();
	if (beizhu == '') {
		layer.alert('微信昵称不能为空！');
		return false;
	}
	//layer.msg('微信还在内测中,此版本暂时不开,交流群：162088255');
	Add_Qr();
}
function Add_Qr() { //添加二维码
	var type = $("#type").val();
	var qr_url = $("#qr_url").val();
	var beizhu = $("#beizhu").val();
	var ii = layer.load(5, {
		shade: [0.1, '#fff']
	});
	$.ajax({
		type: "POST",
		url: "Ajax.php?act=Add_Qr",
		data: {
			type,
			qr_url,
			beizhu
		},
		dataType: 'json',
		timeout: 15000,
		//ajax请求超时时间15s
		success: function(data) {
			layer.close(ii);
			if (data.code == 1) {
				layer.msg(data.msg);
				setTimeout(function() {
					location.href = "?";
				},
				3000); //延时1秒跳转
			}else{
				layer.alert(data.msg, {icon:2}, function(){ location.href = "./"; });//跳转
			}
		},
		error: function(data) {
			layer.close(ii);
			layer.msg('操作失败,服务器错误');
		}
	});
}

///***************************************************************************以下是更新cookie JS   零度(1058551165@qq.com)
///***************wlkjyy重写

function Get_Qr(id) { //拉起弹窗更新
	$("#Up_LoginQrcode").html(''); //清空登陆码
	ZT_QrCode_ID = 'INTL';//云端登陆二维码ID
	ZT_QrCode_URL = 0; //判断是否已经获取到二维码
	Up_QrCode_cookie = 0;//判断是否已经获取COOKIE
	var ii = layer.load(5, {
		shade: [0.1, '#fff']
	});
	$.ajax({
		type: "POST",
		url: "Ajax.php?act=Get_Qr",
		data: {
			id
		},
		dataType: 'json',
		timeout: 15000,
		//ajax请求超时时间15s
		success: function(data) {
			//layer.close(ii);
			//layer.msg('更新:'+data.type);
			layer.close(ii);
			if (data.id) {
				/*
						$("#Up_Qr_modal").modal({
							keyboard: true
						});
						*/

				$(function() {
					setTimeout(function() {
						var buy = document.getElementById('Up_Qr_modal');
						buy.click();
					},
					200);
				});

				$("#Up_Qr_modal").click(function() {
					console.log("wlkjyy")
				})

				$("#Up_Ali_Qr_msg").html('<center>正在提交获取二维码请求...</center>'); //输出提示
				$("#Up_LoginQrcode_msg").html('<center>正在提交获取二维码请求...</center>'); //输出提示
				if (data.type == 'alipay') var is_type = '支付宝';
				else if (data.type == 'qqpay') var is_type = 'QQ';
				else var is_type = '微信';
				$("#Up_id").val(data.id);
				$("#Up_type").val(is_type);
				$("#Up_beizhu").val(data.beizhu); //备注
				Up_id = data.id
				type = data.type; //类型
				beizhu = data.beizhu; //备注
				if (data.type == 'wxpay') {
					var paymsg = '';
					$.each(data.data,
					function(key, value) {
						paymsg += '<button class="btn btn-default btn-block" style="margin-top:10px;"><img width="20" src="/Core/Assets/Icon/wxpay.ico" class="logo">[' + value.wx_name + '] 账号->' + value.wx_user + '</button>';
					});
					$("#Up_beizhu_name").html('微信昵称'); //输出提示
					$("#Up_LoginQrcode_msg").html(paymsg + '<br>请添加以上任意一个微信为好友,并发送店员邀请小程序(搜索[收款小账本]->收款店员->添加店员->邀请微信朋友成为店员->发送给当前微信号即可)'); //输出提示
					$("#Up_LoginQrcode").html('<small style="color:red; font-size:16px">每次微信CK失效都点击一次更新获取最新微信号再发送邀请小程序</small><br>请注意看以下的操作<br>发送之后一段时间未绑定,请联系客服进行审核<br>如果你的微信提示绑定成功了,刷新平台cookie还没更新,请解绑店员再发发送绑定邀请<br>如果在10分钟内未绑定,想要再次绑定的时候必须点击“更新cookie”才能再次发送店员绑定,否则绑定成功了平台cookie也不会更新<br>成功登录的微信请不要随意更换昵称,如果更换昵称,需要删除二维码重新登录绑定!'); //输出登录二维码
				} else {
					ZT_QrCode_ID = 0;
				}

			}
		},
		error: function(data) {
			layer.close(ii);
			layer.msg('操作失败,服务器错误,ID：' + id + data);
			setTimeout(function() {
				location.href = "?";
			},
			3000); //延时1秒跳转
		}
	});
}


function INTL_Zero_setInterval() {
	//开始获取登陆二维码
	if (ZT_QrCode_ID == 0) {
		var ii = layer.load(5, {
			shade: [0.1, '#fff']
		});
		$.get("Ajax.php?act=Get_Login_QrCode", {
			type: type,
			beizhu: beizhu
		},
		function(data) {
			layer.close(ii);
			if (data.qr_url != '') {
				ZT_QrCode_ID = data.id;
				ZT_QrCode_URL = data.qr_url;
				if (type == 'alipay') var is_type = '"支付宝"手机摄像头扫一扫,<small style="color:red; font-size:16px">并关闭支付宝自动转入余额宝,否则到账不回调</small>';
				else if (type == 'qqpay') var is_type = '"QQ"截图发送给QQ好友并识别图片登陆或手机摄像头扫一扫';
				$("#Up_LoginQrcode_msg").html('<center>请您使用' + is_type + '->"2分钟内"扫以下码登录,扫码之后请返回此页面等待1分钟,如超过1分钟则系统登录失败,请您再次重试</center>'); //输出提示
				$("#Up_LoginQrcode").html('<center><img align="center" id="qrcodeimg" alt="加载中..." src="' + data.qr_url +'" title="扫码登录" width="200" height="200" style=" position: relative; border: green solid 1px;"></center>'); //输出登录二维码
			} else if (data.code == -1) {
				layer.close(ii);
				layer.msg(data.msg + '获取登录二维码失败');
				setTimeout(function() {
					location.href = "?";
				},
				3000); //延时1秒跳转
			}
		},
		"JSON");
	}

	//开始检测登陆获取COOKIE并自动更新
	if (ZT_QrCode_ID != 0 && Up_QrCode_cookie == 0) {
		//layer.msg(ZT_QrCode_ID+'微信等待绑定中...');
		$.get("Ajax.php?act=Get_Login_Cookie", {
			id:ZT_QrCode_ID,
			qr_id: Up_id
		},
		function(data) {
			if (data.cookie != '') {
				Up_QrCode_cookie = data.cookie;
				layer.msg('扫码登录成功,正在更新数据...');
				Up_Qr(data.cookie);
			} else if (data.code == -1) {
				ZT_QrCode_ID = 0;
				ZT_QrCode_URL = 0;
				layer.close(ii);
				layer.msg(data.msg + '扫码登录失败');
				setTimeout(function() {
					location.href = "?";
				},
				3000); //延时1秒跳转
			}else if (data.msg == '等待确认中' || data.msg == '等待确认中...') {
				//layer.msg(data.msg+'正在检测COOKIE完整性...'+ZT_QrCode_ID);
				$("#Up_LoginQrcode_msg").html('<center>扫码成功，请在手机上点击确认登录...</center>'); //输出提示
				$("#Up_LoginQrcode").html('<center><img align="center" id="qrcodeimg" alt="加载中..." src="/Core/Assets/Icon/INTL-QR-OK.png" title="扫码成功" width="200" height="200" style=" position: relative; border: green solid 1px;"></center>'); //输出登录二维码
			}
		},
		"JSON");
	}
}

function Up_Qr(cookie) { //更新二维码
	var ii = layer.load(5, {
		shade: [0.1, '#fff']
	});
	$.ajax({
		type: "POST",
		url: "Ajax.php?act=Up_Qr",
		data: {
			id:Up_id,
			cookie
		},
		dataType: 'json',
		timeout: 15000,
		//ajax请求超时时间15s
		success: function(data) {
			layer.close(ii);
			layer.msg(data.msg);
			if (data.code == 1) {
				layer.alert(data.msg, {icon:1}, function(){ location.href = "?"; });//跳转
			}
		},
		error: function(data) {
			layer.close(ii);
			layer.msg('操作失败,服务器错误');
		}
	});
}

//周期监听 
window.setInterval(function() {
	INTL_Zero_setInterval();
},
1000);