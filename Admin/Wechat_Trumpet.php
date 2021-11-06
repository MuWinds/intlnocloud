<?php
// +----------------------------------------------------------------------
// | Quotes [ 只为给微信更好的体验]**[我知道发出来有人会盗用,但请您留版权]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 零度            盗用不留版权,你就不配拿去!
// +----------------------------------------------------------------------
// | Date: 2019年08月20日
// +----------------------------------------------------------------------

/**
 * 微信免挂店员
**/
$title='微信免挂店员';
include './Head.php';
?>

<?php
$my=isset($_GET['my'])?$_GET['my']:null;
if($my=='add')
{
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">添加微信店员</h3></div>
<div class="panel-body">
<div class="form-group">
<label>微信账号(必填):</label><br>
<input type="text" class="form-control" name="wx_user" id="wx_user" value="" placeholder="不可留空">
</div>
<div class="form-group">
<label>微信昵称(必填):</label><br>
<input type="text" class="form-control" name="wx_name" id="wx_name" value="" placeholder="不可留空">
(名称要与微信里的完全符合,不要花里胡哨,不可重复,不可带图标或者特殊符号)
</div>
<div class="form-group">
<label>微信备注:</label><br>
<input class="form-control" name="beizhu" id="beizhu" value="" placeholder="可留空">
</div>
<div class="form-group">
<label>排序(越小越靠前):</label><br>
<input class="form-control" name="sort" id="sort" value="50" placeholder="50">
</div>
<button type="sumbit" class="btn btn-primary btn-block" onclick="add_user();">确定添加</button>
<br/><a href="?">>>返回列表</a>
<?php 
}
elseif($my=='edit')
{
$id=$_GET['id'];
$row=$DB->query("select * from pay_wechat_trumpet where id='$id' limit 1")->fetch();
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">修改微信店员</h3></div>
<div class="panel-body">
<input type="hidden" name="id" id="id" value="<?=$row['id']?>"/>
<div class="form-group">
<label>微信账号(必填):</label><br>
<input type="text" class="form-control" name="wx_user" id="wx_user" value="<?=$row['wx_user']?>" placeholder="不可留空">
</div>
<div class="form-group">
<label>微信昵称:</label><br>
<input type="text" class="form-control" name="wx_name" id="wx_name" value="<?=$row['wx_name']?>" placeholder="不可留空">(名称要与微信里的完全符合,不要花里胡哨,不可重复,不可带图标或者特殊符号)
</div>
<div class="form-group">
<label>微信备注:</label><br>
<input class="form-control" name="beizhu" id="beizhu" value="<?=$row['beizhu']?>" placeholder="可留空">
</div>
<div class="form-group">
<label>排序(越小越靠前):</label><br>
<input class="form-control" name="sort" id="sort" value="<?=$row['sort']?>" placeholder="50">
</div>
<div class="form-group">
<label>上下架:</label><br>
<select class="form-control" name="status" id="status" default="<?php echo $row['status']?>"><option value="1">上架</option><option value="0">下架</option></select></div>
</div>
<button type="sumbit" class="btn btn-primary btn-block" onclick="edit_user();">确定修改</button>
<br/><a href="?">>>返回列表</a>
<?php
}
else
{

echo '
<div class="row">
<div class="col-xs-12 col-lg-12">
	  <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">微信免挂小提示</h3></div>
		<div class="panel-body">
<p>下载微信店员小号监控框架插件->解压->打开可爱猫框架->应用双击->添加微信->登录。</p>
<p>由于需要微信登录PC版的,手机上不能退出也不能切换账号,可以直接卸载微信,重下载然后登录其他微信号,也能保持在线(比较麻烦点)</p>
<p>用户发送邀请连接的时候需手动接受(客户量大可以联系作者弄自动绑定[收费])</p>
<li class="list-group-item" onclick="alert(\'此功能正在破解，敬请期待\')"><a href="javascript:;"class="btn btn-xs btn-info">点此下载站长微信店员登录监控框架插件</a></li>
</div>
</div>

<div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">操作中心</h3></div>
		<div class="panel-body"><form action="" method="GET" class="form-inline"><input type="hidden" name="my" value="search">
  <div class="form-group">
    <label>条件搜索</label>
	<select name="column" class="form-control"><option value="wx_name">微信昵称</option><option value="beizhu">微信备注</option></select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="value" placeholder="搜索内容">
  </div>
  <button type="submit" class="btn btn-primary">搜索</button> <a href="?my=add" class="btn btn-primary">添加微信店员</a>
</form>';

if($my=='search'||$_GET['value']) {
	$sql=" `{$_GET['column']}`='{$_GET['value']}'";
	$numrows=$DB->query("SELECT * from pay_wechat_trumpet WHERE{$sql}")->rowCount();
	$con='包含 '.$_GET['value'].' 的共有 <b>'.$numrows.'</b> 个微信店员';
}else{
	$numrows=$DB->query("SELECT * from pay_wechat_trumpet WHERE 1")->rowCount();
	$sql=" 1";
	$con='系统共有 <b>'.$numrows.'</b> 个微信店员';
}
echo '<br>'.$con;
?>
	</div>
      </div>
	<div class="panel">
        <div class="panel-control"><h3 class="panel-title"><?php echo $title?></h3></div>
	  <form name="form1" id="form1">
	  <div class="table-responsive">
<?php echo $con?>
        <table class="table table-striped table-bordered table-vcenter">
          <thead><tr><th>#</th><th>微信店员账号</th><th>微信店员昵称</th><th>备注</th><th>添加时间</th><th>当前在线状态</th><th>状态</th><th>操作</th></tr></thead>
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

$rs=$DB->query("SELECT * FROM pay_wechat_trumpet WHERE{$sql} order by sort ASC limit $offset,$pagesize");
while($res = $rs->fetch())
{
echo '<tr>
<td><b>'.$res['id'].'</b></td>
<td><b>'.$res['wx_user'].'</b></td>
<td><b>'.$res['wx_name'].'</b></td>
<td><b>'.($res['beizhu']?$res['beizhu']:'未设置备注').'</b></td>
<td><b>'.$res['addtime'].'</b></td>
<td><b>'.wachat_login_zt($res['login_time']).'</b></td>
<td><b>'.wachat_zt($res['status']).'</b></td>
<td><a href="?my=edit&id='.$res['id'].'" class="btn btn-xs btn-info">编辑</a>&nbsp;<a onclick="del_user('.$res['id'].');" class="btn btn-xs btn-info">删除</a></td>
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
  function del_user(id) { //删除
	var confirmobj = layer.confirm('此操作将此数据，是否确定？', {
		btn: ['确定', '取消']
	},
	function() {
		var ii = layer.load(2, {
			shade: [0.1, '#fff']
		});
		$.ajax({
			type: 'POST',
			url : "Ajax.php?act=Del_Wechet_Tp",
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
  function add_user(){//添加微信
        var wx_user= $("#wx_user").val();
        var wx_name= $("#wx_name").val(); 
        var beizhu= $("#beizhu").val(); 
        var sort= $("#sort").val(); 
		if(wx_user=='' || wx_name==''){layer.alert('必填项不能为空！');return false;}
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Add_Wechet_Tp",
				data : {wx_user,wx_name,beizhu,sort},
				dataType : 'json',
				timeout:10000,
				success : function(data) {					  
					  layer.close(ii);
					  layer.msg(data.msg);
					  if(data.code==1){
						setTimeout(function () {
							location.href="./Wechat_Trumpet.php";
						}, 1000); //延时1秒跳转
					  }
				},
				error:function(data){
					layer.close(ii);
					layer.msg('服务器错误');
					}
			});
		  	
}
  function edit_user(){//修改微信
        var id= $("#id").val(); 
        var wx_user= $("#wx_user").val(); 
        var wx_name= $("#wx_name").val(); 
        var beizhu= $("#beizhu").val(); 
        var sort= $("#sort").val();
        var status= $("#status").val(); 
		if(wx_user=='' || wx_name==''){layer.alert('必填项不能为空！');return false;}
		  	var ii = layer.load(3, {shade:[0.1,'#fff']});
		  	$.ajax({
				type : "POST",
				url : "Ajax.php?act=Edit_Wechet_Tp",
				data : {id,wx_user,wx_name,beizhu,sort,status},
				dataType : 'json',
				timeout:10000,
				success : function(data) {					  
					  layer.close(ii);
					  layer.msg(data.msg);
					  if(data.code==1){
						setTimeout(function () {
							location.href="./Wechat_Trumpet.php";
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