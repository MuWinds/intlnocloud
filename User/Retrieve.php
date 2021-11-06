<!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>找回Pid、Key | <?=$conf['sitename']?>-<?=$conf['title']?></title>
	  
		<!-- Mobile Metas -->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		<!-- Import google fonts -->
        <link href='http://fonts.useso.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
        
		<!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="/Core/Assets/Assets/ico/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="/Core/Assets/Assets/ico/apple-touch-icon.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="/Core/Assets/Assets/ico/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/Core/Assets/Assets/ico/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="/Core/Assets/Assets/ico/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/Core/Assets/Assets/ico/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="/Core/Assets/Assets/ico/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/Core/Assets/Assets/ico/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="/Core/Assets/Assets/ico/apple-touch-icon-152x152.png" />
		
	    <!-- start: CSS file-->
		
		<!-- Vendor CSS-->
		<link href="/Core/Assets/Assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="/Core/Assets/Assets/vendor/skycons/css/skycons.css" rel="stylesheet" />
		<link href="/Core/Assets/Assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		
		<!-- Plugins CSS-->
		<link href="/Core/Assets/Assets/plugins/bootkit/css/bootkit.css" rel="stylesheet" />	
		
		<!-- Theme CSS -->
		<link href="/Core/Assets/Assets/css/jquery.mmenu.css" rel="stylesheet" />
		
		<!-- Page CSS -->		
		<link href="/Core/Assets/Assets/css/style.css" rel="stylesheet" />
		<link href="/Core/Assets/Assets/css/add-ons.min.css" rel="stylesheet" />
		
		<style>
			footer {
				display: none;
			}
		</style>
		
		<!-- end: CSS file-->	
	    
		
		<!-- Head Libs -->
		<script src="/Core/Assets/Assets/plugins/modernizr/js/modernizr.js"></script>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->		
		
	</head>

	<body>
		<!-- Start: Content -->
		<div class="container-fluid content">
			<div class="row">
				<!-- Main Page -->
				<div id="content" class="col-sm-12 full">
					<div class="row">
						<div class="recover-box">
							<div class="panel">
								<div class="panel-body">								
									<div class="header bk-margin-bottom-20 text-center">										
										<img src="/Core/Assets/Img/logo.png" class="img-responsive" alt="" />
										<h4 class="bk-fg-danger"><i class="fa fa-user"></i> 找回Pid、Key</h4>
									</div>
									<div class="alert alert-info text-center">
										<p>Enter your e-mail below and we will send you reset instructions!</p>
									</div>
										<div class="form-group">
											<div class="input-group">
												<input name="qq" id="qq" type="text" placeholder="输入绑定的QQ号" class="form-control" />
												<span class="input-group-btn">
													<button onclick="check_Retrieve();" class="btn btn-info">Reset!</button>
												</span>
											</div>
										</div>

										<p class="text-center">已记得? <a href="Login.php">去登陆!</a>
									</form>
								</div>
							</div>		
				</div>
				<!-- End Main Page -->			
			</div>
		</div><!--/container-->
		
		
		<!-- start: JavaScript-->
		
		<!-- Vendor JS-->				
		<script src="/Core/Assets/Assets/vendor/js/jquery.min.js"></script>
		<script src="/Core/Assets/Assets/vendor/js/jquery-2.1.1.min.js"></script>
		<script src="/Core/Assets/Assets/vendor/js/jquery-migrate-1.2.1.min.js"></script>
		<script src="/Core/Assets/Assets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="/Core/Assets/Assets/vendor/skycons/js/skycons.js"></script>	
		
		<!-- Plugins JS-->
		<script src="/Core/Assets/Assets/plugins/bootkit/js/bootkit.js"></script>
		
		<!-- Theme JS -->		
		<script src="/Core/Assets/Assets/js/jquery.mmenu.min.js"></script>
		<script src="/Core/Assets/Assets/js/core.min.js"></script>
		
		<!-- Pages JS -->
		<script src="/Core/Assets/Assets/js/pages/page-login.js"></script>
		<script src="../Core/Assets/Layer/layer.js"></script>
<script type="text/javascript">
	function check_Retrieve()
	{
		var qq=$("#qq").val();;
		var ii = layer.load(5, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "Ajax.php?act=Retrieve",
			data : {qq},
			dataType : 'json',
			timeout: 15000, //ajax请求超时时间15s
			success : function(data) {					  
				layer.close(ii);
				layer.msg(data.msg);
				if(data.code==1){
					setTimeout(function () {
					location.href="./Login.php";
					}, 1000); //延时1秒跳转
				}else{
					//layer.msg(data.msg);
				}
			},
			error:function(data){
				layer.close(ii);
				layer.msg('服务器错误');
				}
		});
	}
	</script>
		<!-- end: JavaScript-->
		
	</body>
	
</html>