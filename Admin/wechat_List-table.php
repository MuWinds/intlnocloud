<?php
/**
 * 订单列表
**/
include("../Core/Common.php");
if($islogin_admin==1){}else exit("<script language='javascript'>window.location.href='./Login.php';</script>");


function display_status($status,$notify){
	if($status==1)
		$msg = '<font color=green>已支付</font>';
	elseif($status==2)
		$msg = '<font color=red>已退款</font>';
	elseif($status==3)
		$msg = '<font color=red>已冻结</font>';
	else
		$msg = '<font color=blue>未支付</font>';
	if($notify==0 && $status>0)
		$msg .= '<br/><font color=green>通知成功</font>';
	elseif($status>0)
		$msg .= '<br/><font color=red>通知失败</font>';
	return $msg;
}
function qr_zt($price,$apittime)//出码状态
{
	if($price>0.00)
		return '<font color=green>与云端通讯完成</font>';
	elseif($res['apittime']>=time())
		return '<font color=red>正在与云端通讯...</font>';
	else
		return '<font color=red>与云端通讯失败,当前云端服务器可能故障</font>';
}
function display_operation($status, $id){
	if($status==1)
		$msg = '<li><a href="javascript:WesetStatus(\''.$id.'\', 0)">改未绑定登录</a></li></li><li role="separator" class="divider"></li><li><a href="javascript:WesetStatus(\''.$id.'\', 5)">删除</a></li>';
	else
		$msg = '<li><a href="javascript:WesetStatus(\''.$id.'\', 1)">改已绑定登录</a></li><li role="separator" class="divider"></li><li><a href="javascript:WesetStatus(\''.$id.'\', 5)">删除</a></li>';
	return $msg;
}
$paytype = [];
$paytypes = [];
//$rs = $DB->getAll("SELECT * FROM pre_type");
foreach($rs as $row){
	$paytype[$row['id']] = $row['showname'];
	$paytypes[$row['id']] = $row['name'];
}
unset($rs);

$sqls="";
$links='';
if(isset($_GET['pid']) && !empty($_GET['pid'])) {
	$pid = intval($_GET['pid']);
	$sqls.=" AND `pid`='$pid'";
	$links.='&pid='.$pid;
}
if(isset($_GET['dstatus']) && $_GET['dstatus']>0) {
	$dstatus = intval($_GET['dstatus']);
	$dstatus = $dstatus==2?0:$dstatus;
	$sqls.=" AND status={$dstatus}";
	$links.='&dstatus='.$dstatus;
}
if(isset($_GET['value']) && !empty($_GET['value'])) {
	if($_GET['column']=='name'){
		$sql=" `{$_GET['column']}` like '%{$_GET['value']}%'";
	}elseif($_GET['column']=='addtime'){
		$kws = explode('>',$_GET['value']);
		$sql=" AND `addtime`>='{$kws[0]}' AND `addtime`<='{$kws[1]}'";
	}else{
		$sql=" `{$_GET['column']}`='{$_GET['value']}'";
	}
	$sql.=$sqls;
	$numrows=$DB->query("SELECT * from pay_qrlist WHERE 1")->rowCount();
	$con='包含 '.$_GET['value'].' 的共有 <b>'.$numrows.'</b> 条订单';
	$link='&column='.$_GET['column'].'&value='.$_GET['value'].$links;
}else{
	$sql=" 1";
	$sql.=$sqls;
	$numrows=$DB->query("SELECT * from pay_qrlist WHERE 1")->rowCount();
	$con='共有 <b>'.$numrows.'</b> 条订单';
	$link=$links;
}
?>
	  <form name="form1" id="form1">
	  <div class="table-responsive">
<?php echo $con?>
        <table class="table table-striped table-bordered table-vcenter">
          <thead><tr><th>#</th><th>商户号</th><th>微信昵称</th><th>绑定的微信店员</th><th>状态/更新时间/运行时间</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pay_qrlist WHERE type='wxpay' and {$sql} order by addtime desc limit $offset,$pagesize");
while($res = $rs->fetch())
{
echo '<tr>
<td><b><input type="checkbox" name="checkbox[]" id="list1" value="'.$res['id'].'" onClick="unselectall1()"><b>'.$res['id'].'</b></td>
<td><b>'.($res['pid']>0?'<a href="./User.php?my=search&column=pid&value='.$res['pid'].'" target="_blank">'.$res['pid'].'</a>':'管理员').'</b></td>
<td>'.$res['beizhu'].'</td>
<td>'.($res['wx_name']?$res['wx_name']:'未绑定').'</td>
<td>'.cookie_zt($res['status'],$res['type'],$res['addtime'],$res['endtime']).'</td>
<td><div class="btn-group" role="group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作 <span class="caret"></span></button><ul class="dropdown-menu">'.display_operation($res['status'], $res['id']).'</ul></div></td>
</tr>';
}
?>
          </tbody>
        </table>
<input name="chkAll1" type="checkbox" id="chkAll1" onClick="this.value=check1(this.form.list1)" value="checkbox">&nbsp;全选&nbsp;
<select name="status"><option selected>多选操作</option><option value="0">改未绑定登录</option><option value="1">改已绑定登录</option><option value="4">删除</option></select>
<button type="button" onclick="operation()">确定</button>
      </div>
	 </form>
<?php
echo'<div class="text-center"><ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$first.$link.'\')">首页</a></li>';
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$prev.$link.'\')">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$i.$link.'\')">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$i.$link.'\')">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$next.$link.'\')">&raquo;</a></li>';
echo '<li><a href="javascript:void(0)" onclick="listTable(\'page='.$last.$link.'\')">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul></div>';
