<?php
/**
 * 公告设置
**/
$title='公告设置';
include './Head.php';
?>
<link href="//cdn.staticfile.org/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet"/>
<?php
$my=isset($_GET['my'])?$_GET['my']:null;
if($my=='edit'){
	$id=intval($_GET['id']);
	$rows=$DB->query("SELECT * FROM `pay_notice` WHERE `id`='{$id}' limit 1")->fetch();
	if(!$rows)
		showmsg('当前公告不存在！',3);
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">修改公告(ID:<?php echo $id?>)</h3></div>
<div class="panel-body">
	<form action="./Notice.php?my=edit_submit&id=<?php echo $id?>" role="form" class="form-horizontal" method="post">
		<div class="list-group-item">
			<div class="input-group">
				<div class="input-group-addon">公告标题</div>
				<input type="text" name="title" value="<?php echo $rows['title']?>" class="form-control" required/>
			</div>
		<div class="list-group-item">
			<div class="input-group">
				<div class="input-group-addon">公告内容</div>
				<textarea class="form-control" name="datatxt" rows="5" placeholder="输入公告内容" required><?php echo $rows['datatxt']?></textarea>
			</div>
		</div>
		<div class="list-group-item form-inline">
			<div class="input-group">
				<div class="input-group-addon">排序</div>
				<input type="text" name="sort" value="<?php echo $rows['sort']?>" class="form-control" required/>
			</div>
			<div class="input-group input-colorpicker colorpicker-element">
				<input type="text" name="color" value="<?php echo $rows['color']?>" class="form-control" placeholder="文字颜色" maxlength="7"/>
				<span class="input-group-addon"><i></i></span>
			</div>
		</div>
		<div class="list-group-item">
			<input type="submit" value="保存" class="btn btn-primary btn-block">
		</div>
	</form>
</div>
</div>
<?php
}
elseif($my=='add_submit'){
$title=$_POST['title'];
$datatxt=$_POST['datatxt'];
$sort=intval($_POST['sort']);
$color=trim($_POST['color']);
if(!$title || !$datatxt || !$sort){
showmsg('公告标题、内容不能为空',3);
} else {
$sds=$DB->exec("INSERT INTO `pay_notice` (`title`, `datatxt`, `color`, `sort`, `addtime`, `status`) VALUES ('{$title}','{$datatxt}', '{$color}', '{$sort}', '{$date}', 1)");
if($sds){
	showmsg('添加公告成功！<br/><br/><a href="./Notice.php">>>返回公告列表</a>',1);
}else
	showmsg('添加公告失败！<br/>错误信息：'.$DB->error(),4);
}
}
elseif($my=='edit_submit'){
$id=intval($_GET['id']);
$rows=$DB->query("SELECT * FROM `pay_notice` WHERE `id`='{$id}' limit 1")->fetch();
if(!$rows)
	showmsg('当前公告不存在！',3);
$title=$_POST['title'];
$datatxt=$_POST['datatxt'];
$sort=intval($_POST['sort']);
$color=trim($_POST['color']);
if(!$title || !$datatxt || !$sort){
showmsg('公告标题、内容不能为空',3);
} else {
$sds=$DB->exec("UPDATE `pay_notice` SET `title`='$title',`datatxt`='$datatxt',`sort`='$sort',`color`='$color' WHERE `id`='$id'");
if($sds!==false){
	showmsg('修改公告成功！<br/><br/><a href="./Notice.php">>>返回公告列表</a>',1);
}else
	showmsg('修改公告失败！<br/>错误信息：'.$DB->error(),4);
}
}else{
$list=$DB->query("SELECT * FROM pay_notice WHERE1 order by sort ASC");
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">添加公告</h3></div>
<div class="panel-body">
	<form action="./Notice.php?my=add_submit" role="form" class="form-horizontal" method="post">
		<div class="list-group-item">
			<div class="input-group">
				<div class="input-group-addon">公告标题</div>
				<input type="text" name="title" value="<?php echo $rows['title']?>" class="form-control" required/>
			</div>
		<div class="list-group-item">
			<div class="input-group">
				<div class="input-group-addon">公告内容</div>
				<textarea class="form-control" name="datatxt" rows="5" placeholder="输入公告内容" required></textarea>
			</div>
		</div>
		<div class="list-group-item form-inline">
			<div class="input-group">
				<div class="input-group-addon">排序</div>
				<input type="text" name="sort" value="50" class="form-control" required/>
			</div>
			<div class="input-group input-colorpicker colorpicker-element">
				<input type="text" name="color" value="" class="form-control" placeholder="文字颜色" maxlength="7"/>
				<span class="input-group-addon"><i></i></span>
			</div>
		</div>
		<div class="list-group-item">
			<input type="submit" value="添加" class="btn btn-primary btn-block">
			<a href="./Set.php?mod=Notice" class="btn btn-default btn-block">弹出公告与首页底部设置</a>
		</div>
	</form>
</div>
</div>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">已发公告</h3></div>
<div class="panel-body">
<?php foreach($list as $row){?>
		<div class="list-group-item">
			<em class="fa fa-fw fa-volume-up"></em><font color="<?php echo $row['color']?$row['color']:null?>"><?php echo $row['datatxt']?></font><small>&nbsp;-<?php echo $row['addtime']?></small>&nbsp;&nbsp;<?php echo $row['status']==1?'<span class="btn btn-xs btn-success" onclick="setStatus('.$row['id'].',0)">显示</span>':'<span class="btn btn-xs btn-warning" onclick="setStatus('.$row['id'].',1)">隐藏</span>'?>&nbsp;<a class="btn btn-xs btn-info" href="./Notice.php?my=edit&id=<?php echo $row['id']?>">编辑</a>&nbsp;<a class="btn btn-xs btn-danger" href="javascript:delItem(<?php echo $row['id']?>)">删除</a>
		</div>
<?php }?>
</div>
</div>
<?php }?>
 </div>
</div>
<script src="//cdn.staticfile.org/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script>
function setStatus(id,status) {
	$.ajax({
		type : 'GET',
		url : 'Ajax.php?act=setNotice&id='+id+'&status='+status,
		dataType : 'json',
		success : function(data) {
			if(data.code == 0){
				window.location.reload();
			}else{
				layer.msg(data.msg, {icon:2, time:1500});
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
function delItem(id) {
	var confirmobj = layer.confirm('你确实要删除此公告吗？', {
		btn: ['确定','取消']
	}, function(){
		$.ajax({
			type : 'GET',
			url : 'Ajax.php?act=delNotice&id='+id,
			dataType : 'json',
			success : function(data) {
				if(data.code == 0){
					window.location.reload();
				}else{
					layer.msg(data.msg, {icon:2, time:1500});
				}
			},
			error:function(data){
				layer.msg('服务器错误');
				return false;
			}
		});
	});
}
$(document).ready(function(){
	$('.input-colorpicker').colorpicker({format: 'hex'});
})
</script>