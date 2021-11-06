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
 * 收款记录
**/
$title='收款记录';
include './Head.php';
?>

<?php

echo '<div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">操作中心</h3></div>
		<div class="panel-body"><form action="" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
  <div class="form-group">
    <label>条件搜索</label>
	<select name="column" class="form-control"><option value="id">ID</option></select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="搜索内容">
  </div>
  <button type="submit" class="btn btn-primary">搜索</button> 
</form>';

if($_GET['my']=='search'||$_GET['value']) {
	$sql=" `{$_GET['column']}`='{$_GET['value']}'";
	$numrows=$DB->query("SELECT * from pay_notify WHERE{$sql}")->rowCount();
	$con='包含 '.$_GET['value'].' 的共有 <b>'.$numrows.'</b> 个二维码';
}else{
	$numrows=$DB->query("SELECT * from pay_notify")->rowCount();
	$nums=$DB->query("SELECT * from pay_notify WHERE status='1'")->rowCount();
	$sql=" 1";
	//$con='系统共有 <b>'.$numrows.'</b> 个收款记录,已经自动回调:<b>'.$nums.'</b>个';
	$con='系统共有 <b>'.$numrows.'</b> 个收款记录';
}
echo '<br>'.$con;
?>
	</div>
      </div>
      </div>
	<div class="panel">
        <div class="panel-control"><h3 class="panel-title"><?php echo $title?></h3></div>
	  <form name="form1" id="form1">
	  <div class="table-responsive">
<?php echo $con?>
        <table class="table table-striped table-bordered table-vcenter"><thead>
												<tr>
													<th>ID</th>
													<th>商户PID</th>
													<th>二维码</th>
													<th>二维码备注</th>
													<th>收款金额</th>
              										<!--th>回调状态</th>
              										<th>回调返回数据</th-->
													<th>收款时间(系统检测时间)</th>
													</tr>
													</thead>
          <tbody>
<?php
$pagesize=30;
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize)
{
 $pages++;
 }
if (isset($_GET['page'])){
$page=intval($_GET['page']);
}
else{
$page=1;
}
$offset=$pagesize*($page - 1);
function notify_zt($status)//出码状态
{
	if($status==1)
		return '<font color=green>回调成功</font>';
	elseif($status==2)
		return '<font color=red>回调失败</font>';
	else
		return '<font color=red>暂未回调</font>';
}
$rs=$DB->query("SELECT * FROM pay_notify WHERE{$sql} order by addtime desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
	$row=$DB->query("select * from pay_qrlist where id='{$res['qr_id']}' limit 1")->fetch();
	echo '<tr><td>'.$res['id'].'</td>
	<td>'.$res['pid'].'</td>
	<td>'.$res['qr_id'].'->'.($row['type']?'<img src="/Core/Assets/Icon/'.$row['type'].'.ico" width="16" onerror="this.style.display=\'none\'">'.pay_type($row['type']):'此码已被删除').'</td>
	<td>'.($res['qr_beizhu']?$res['qr_beizhu']:'无备注').'</td>
	<td><b>￥ '.$res['money'].'</b></td>
	<!--td>'.notify_zt($res['status']).'</td>
	<td>'.$res['pay_msg'].'</td-->
	<td>'.$res['addtime'].'</td>
	</tr>';
}
?>
          </tbody>
        </table>
      </div>
<?php
echo'<ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$pages;$i++)
echo '<li><a href="?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
?>
    </div>
  </div>
  
  <script type="text/javascript">
  function del_user(id){//删除用户
		if(id==''){layer.alert('请确保各项不能为空！');return false;}
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Del_Qrlist",
				data : {id:id},
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
  function add_user(){//添加用户
        var beizhu= $("#beizhu").val();  
        var qr_url= $("#qr_url").val();
        var cookie= $("#cookie").val(); 
        var xm_alipay= $("#xm_alipay").val();  
        var zh_alipay= $("#zh_alipay").val();  
		if(qr_url=='' || beizhu=='' || cookie=='' || xm_alipay=='' || zh_alipay==''){layer.alert('必填项不能为空！');return false;}
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Add_Qrlist",
				data : {beizhu,cookie,xm_alipay,zh_alipay},
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
  function edit_user(){//修改用户
        var id= $("#id").val();  
        var id= $("#id").val();  
        var cookie= $("#cookie").val();  
		if(cookie==''){layer.alert('必填项不能为空！');return false;}
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Edit_Qrlist",
				data : {id,cookie},
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



$(document).ready(function() {
	
	QrCode_id = 0;//登陆二维码识别ID
	Update_Get_File= 0;//判断是否成功上传二维码
	Update_Get_QrUrl= 0;//判断是否成功提交获取登陆二维码申请
	Update_QrUrl= 0;//判断是否已经获取到登陆二维码
	Update_Get_Cookie = 0;//判断是否已经获取到COOKIE
	
            $('.picurl > input').bind('focus mouseover', function() {
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
		var ii = layer.load(3, {shade:[0.1,'#fff']});
        var file = document.getElementById("imgfile").files[0];
        var formData = new FormData();
        formData.append('image_field', file);
        $.ajax({
			url : "Ajax.php?act=Add_Qrcode",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            mimeType: "multipart/form-data",
            dataType:'json',
            success: function (data) {
									/*layer.close(ii);
									$('#qr_url').val(data.qr_url);	  
							*/
					if(data.code==1){
							layer.close(ii);
							$('#qr_url').val(data.qrcode);
					}else{
							layer.close(ii);
							layer.msg(data.msg);
							setTimeout(function () {
								location.href="?";
							}, 3000); //延时1秒跳转
					}
		},
		error:function(data){
			layer.close(ii);
			layer.msg('请剪切边框或更换其他二维码重试');
			setTimeout(function () {
				location.href="?";
			}, 3000); //延时1秒跳转
		}
	});
}
  </script>