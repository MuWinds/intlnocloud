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
		url: "Ajax.php?act=Add_Qr_Pc",
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