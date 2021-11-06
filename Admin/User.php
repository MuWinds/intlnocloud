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
 * 用户管理
**/
$title='用户管理';
include './Head.php';
?>

<?php
$my=isset($_GET['my'])?$_GET['my']:null;
if($my=='add')
{
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">添加用户</h3></div>
<div class="panel-body">
<div class="form-group">
<label>PID:</label><br>
<pre class="form-control"><font color="green">PID系统随机给出的</font></pre>
</div>
<div class="form-group">
<label>Key:</label><br>
<input type="text" class="form-control" name="key" id="key" value="" placeholder="不可留空">
</div>
<div class="form-group">
<label>qq:</label><br>
<input class="form-control" name="qq" id="qq" value="" placeholder="可留空">
</div>
<div class="form-group">
<label>额度:</label><br>
<input class="form-control" name="money" id="money" value="0.00" placeholder="0.00">
</div>
<button type="sumbit" class="btn btn-primary btn-block" onclick="add_user();">确定添加</button>
<br/><a href="?">>>返回列表</a>
<?php 
}
elseif($my=='edit')
{
$pid=$_GET['pid'];
$row=$DB->query("select * from pay_user where pid='$pid' limit 1")->fetch();
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">修改用户</h3></div>
<div class="panel-body">
<input type="hidden" name="pid" id="pid" value="<?=$row['pid']?>"/>
<div class="form-group">
<label>Key:</label><br>
<input type="text" class="form-control" name="key" id="key" value="<?=$row['key']?>" placeholder="不可留空">
</div>
<div class="form-group">
<label>qq:</label><br>
<input class="form-control" name="qq" id="qq" value="<?=$row['qq']?>" placeholder="可留空">
</div>
<div class="form-group">
<label>额度:</label><br>
<input class="form-control" name="money" id="money" value="<?=$row['money']?>" placeholder="可留空">
</div>
<div class="form-group">
<label>支付宝免挂会员到期时间:</label><br>
<input class="form-control" type="date" name="alipay_free_vip_time" id="alipay_free_vip_time" value="<?=$row['alipay_free_vip_time']?>" placeholder="可留空">
<font color="green">不填写此项则不修改</font>
</div>
<div class="form-group">
<label>QQ钱包免挂会员到期时间:</label><br>
<input class="form-control" type="date" name="qqpay_free_vip_time" id="qqpay_free_vip_time" value="<?=$row['qqpay_free_vip_time']?>" placeholder="可留空">
<font color="green">不填写此项则不修改</font>
</div>
<div class="form-group">
<label>微信免挂会员到期时间:</label><br>
<input class="form-control" type="date" name="wxpay_free_vip_time" id="wxpay_free_vip_time" value="<?=$row['wxpay_free_vip_time']?>" placeholder="可留空">
<font color="green">不填写此项则不修改</font>
</div>
<button type="sumbit" class="btn btn-primary btn-block" onclick="edit_user();">确定修改</button>
<br/><a href="?">>>返回列表</a>
<?php
}
else
{

echo '<div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">操作中心</h3></div>
		<div class="panel-body"><form action="" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
  <div class="form-group">
    <label>条件搜索</label>
	<select name="column" class="form-control"><option value="pid">商户PID</option></select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="搜索内容">
  </div>
  <button type="submit" class="btn btn-primary">搜索</button> <a href="?my=add" class="btn btn-primary">添加用户</a>
</form>';

if($my=='search'||$_GET['value']) {
	$sql=" `{$_GET['column']}`='{$_GET['value']}'";
	$numrows=$DB->query("SELECT * from pay_user WHERE{$sql}")->rowCount();
	$con='包含 '.$_GET['value'].' 的共有 <b>'.$numrows.'</b> 个用户';
}else{
	$numrows=$DB->query("SELECT * from pay_user WHERE 1")->rowCount();
	$sql=" 1";
	$con='系统共有 <b>'.$numrows.'</b> 个用户';
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
        <table class="table table-striped table-bordered table-vcenter">
          <thead><tr><th>pid/key</th><th>自定义登陆账号/密码</th><th>QQ号/额度</th><th>旗下二维码数量</th><th>免挂会员</th><th>添加时间/操作</th></tr></thead>
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

$rs=$DB->query("SELECT * FROM pay_user WHERE{$sql} order by addtime desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
	$alipay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='alipay' and `pid`='{$res['pid']}'")->fetchColumn();
	$wxpay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='wxpay' and `pid`='{$res['pid']}'")->fetchColumn();
	$qqpay_cookie_ok=$DB->query("SELECT count(*) from pay_qrlist where status='1' and type='qqpay' and `pid`='{$res['pid']}'")->fetchColumn();
	
	$alipay_cookie_no=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='alipay' and `pid`='{$res['pid']}'")->fetchColumn();
	$wxpay_cookie_no=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='wxpay' and `pid`='{$res['pid']}'")->fetchColumn();
	$qqpay_cookie_no=$DB->query("SELECT count(*) from pay_qrlist where status!='1' and type='qqpay' and `pid`='{$res['pid']}'")->fetchColumn();
echo '<tr>
<td><b>'.$res['pid'].'</b><br><b>'.$res['key'].'</b></td>
<td><b>'.($res['user']?$res['user']:'未设置').'</b><br><b>'.($res['pass']?$res['pass']:'未设置').'</b></td>
<td><b>'.$res['qq'].'</b><br><b>'.$res['money'].'</b></td>
<td><img src="/Core/Assets/Icon/alipay.ico" width="16" onerror="this.style.display=\'none\'"><b>正常：'.($alipay_cookie_ok?'<font color=green>'.$alipay_cookie_ok.'</font>':'<font color=green>0</font>').' 失效：'.($alipay_cookie_no?'<font color=font>'.$alipay_cookie_no.'</font>':'<font color=font>0</font>').'</b><br>
<img src="/Core/Assets/Icon/qqpay.ico" width="16" onerror="this.style.display=\'none\'"><b>正常：'.($qqpay_cookie_ok?'<font color=green>'.$qqpay_cookie_ok.'</font>':'<font color=green>0</font>').' 失效：'.($qqpay_cookie_no?'<font color=font>'.$qqpay_cookie_no.'</font>':'<font color=font>0</font>').'</b><br>
<img src="/Core/Assets/Icon/wxpay.ico" width="16" onerror="this.style.display=\'none\'"><b>正常：'.($wxpay_cookie_no?'<font color=green>'.$wxpay_cookie_no.'</font>':'<font color=green>0</font>').' 失效：'.($wxpay_cookie_no?'<font color=font>'.$wxpay_cookie_no.'</font>':'<font color=font>0</font>').'</b></td>
<td><img src="/Core/Assets/Icon/alipay.ico" width="16" onerror="this.style.display=\'none\'"><b>'.($res['alipay_free_vip_time']>$date?'<font color=green>'.$res['alipay_free_vip_time'].'</font>':'<font color=red>未开通或到期了哦</font>').'</b><br>
<img src="/Core/Assets/Icon/qqpay.ico" width="16" onerror="this.style.display=\'none\'"><b>'.($res['qqpay_free_vip_time']>$date?'<font color=green>'.$res['qqpay_free_vip_time'].'</font>':'<font color=red>未开通或到期了哦</font>').'</b><br>
<img src="/Core/Assets/Icon/wxpay.ico" width="16" onerror="this.style.display=\'none\'"><b>'.($res['wxpay_free_vip_time']>$date?'<font color=green>'.$res['wxpay_free_vip_time'].'</font>':'<font color=red>未开通或到期了哦</font>').'</b></td>
<td><b>'.$res['addtime'].'</b><br><a href="?my=edit&pid='.$res['pid'].'" class="btn btn-xs btn-info">编辑</a>&nbsp;<a onclick="del_user('.$res['pid'].');" class="btn btn-xs btn-info">删除</a></td>
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
}
?>
    </div>
  </div>
  
  <script type="text/javascript">
  function del_user(pid) { //删除二维吗
	var confirmobj = layer.confirm('此操作将此数据，是否确定？', {
		btn: ['确定', '取消']
	},
	function() {
		var ii = layer.load(2, {
			shade: [0.1, '#fff']
		});
		$.ajax({
			type: 'POST',
			url : "Ajax.php?act=Del_user",
			data: {
				pid
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
  function add_user(){//添加用户
        var key= $("#key").val(); 
        var qq= $("#qq").val(); 
        var money= $("#money").val();   
		if(key==''){layer.alert('必填项不能为空！');return false;}
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Add_user",
				data : {key,qq,money},
				dataType : 'json',
				timeout:10000,
				success : function(data) {					  
					  layer.close(ii);
					  layer.msg(data.msg);
					  if(data.code==1){
						setTimeout(function () {
							location.href="./User.php";
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
        var pid= $("#pid").val(); 
        var key= $("#key").val(); 
        var qq= $("#qq").val(); 
        var money= $("#money").val(); 
        var alipay_free_vip_time= $("#alipay_free_vip_time").val();  
        var qqpay_free_vip_time= $("#qqpay_free_vip_time").val();  
        var wxpay_free_vip_time= $("#wxpay_free_vip_time").val(); 
		if(key==''){layer.alert('必填项不能为空！');return false;}
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Edit_user",
				data : {pid,key,qq,money,alipay_free_vip_time,qqpay_free_vip_time,wxpay_free_vip_time},
				dataType : 'json',
				timeout:10000,
				success : function(data) {					  
					  layer.close(ii);
					  layer.msg(data.msg);
					  if(data.code==1){
						setTimeout(function () {
							location.href="./User.php";
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