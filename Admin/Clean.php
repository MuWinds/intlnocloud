<?php
/**
 * 系统数据清理
**/
$title='系统数据清理';
include './Head.php';
?>
<?php
$mod=isset($_GET['mod'])?$_GET['mod']:null;
if($mod=='Cleancache'){
$CACHE->clear();
if(function_exists("opcache_reset"))@opcache_reset();
showmsg('清理系统设置缓存成功！',1);
}elseif($mod=='Cleanorder'){
$DB->exec("DELETE FROM `pay_order` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-30 days"))."'");
$DB->exec("OPTIMIZE TABLE `pay_order`");
showmsg('删除30天前订单记录成功！',1);
}elseif($mod=='Cleansettle'){
$DB->exec("DELETE FROM `pay_settle` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-30 days"))."'");
$DB->exec("OPTIMIZE TABLE `pay_settle`");
showmsg('删除30天前结算记录成功！',1);
}elseif($mod=='Cleanrecord'){
$DB->exec("DELETE FROM `pay_record` WHERE date<'".date("Y-m-d H:i:s",strtotime("-30 days"))."'");
$DB->exec("OPTIMIZE TABLE `pay_record`");
showmsg('删除30天前资金明细成功！',1);
}elseif($mod=='Cleanorderi' && $_POST['do']=='submit'){
$days = intval($_POST['days']);
if($days<=0)showmsg('请确保每项不能为空',3);
$DB->exec("DELETE FROM `pay_order` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-{$days} days"))."'");
$DB->exec("OPTIMIZE TABLE `pay_order`");
showmsg('删除订单记录成功！',1);
}elseif($mod=='Cleansettlei' && $_POST['do']=='submit'){
$days = intval($_POST['days']);
if($days<=0)showmsg('请确保每项不能为空',3);
$DB->exec("DELETE FROM `pay_settle` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-{$days} days"))."'");
$DB->exec("OPTIMIZE TABLE `pay_settle`");
showmsg('删除结算记录成功！',1);
}elseif($mod=='Cleanrecordi' && $_POST['do']=='submit'){
$days = intval($_POST['days']);
if($days<=0)showmsg('请确保每项不能为空',3);
$DB->exec("DELETE FROM `pay_record` WHERE date<'".date("Y-m-d H:i:s",strtotime("-{$days} days"))."'");
$DB->exec("OPTIMIZE TABLE `pay_record`");
showmsg('删除资金明细成功！',1);
}else{
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">系统数据清理</h3></div>
<div class="panel-body">
<a href="./Clean.php?mod=Cleancache" class="btn btn-block btn-default">清理设置缓存</a><br/>
<a href="./Clean.php?mod=Cleanorder" onclick="return confirm('你确实要删除30天前的订单记录吗？');" class="btn btn-block btn-default">删除30天前订单记录</a><br/>
<!--a href="./Clean.php?mod=Cleansettle" onclick="return confirm('你确实要删除30天前的结算记录吗？');" class="btn btn-block btn-default">删除30天前结算记录</a><br/>
<a href="./Clean.php?mod=Cleanrecord" onclick="return confirm('你确实要删除30天前的资金明细吗？');" class="btn btn-block btn-default">删除30天前资金明细</a><br/-->
<h4>自定义清理：</h4>
<form action="./Clean.php?mod=Cleanorderi" method="post" role="form"><input type="hidden" name="do" value="submit"/>
<b>订单记录</b>：<input type="text" name="days" value="" placeholder="天数"/>天前的订单记录&nbsp;<input type="submit" name="submit" value="立即删除" class="btn btn-sm btn-danger" onclick="return confirm('删除后无法恢复，确定继续吗？');"/>
</form><br/>
<form action="./Clean.php?mod=Cleansettlei" method="post" role="form"><input type="hidden" name="do" value="submit"/>
<!--b>结算记录</b>：<input type="text" name="days" value="" placeholder="天数"/>天前的结算记录&nbsp;<input type="submit" name="submit" value="立即删除" class="btn btn-sm btn-danger" onclick="return confirm('删除后无法恢复，确定继续吗？');"/>
</form><br/>
<form action="./Clean.php?mod=Cleanrecordi" method="post" role="form"><input type="hidden" name="do" value="submit"/>
<b>资金明细</b>：<input type="text" name="days" value="" placeholder="天数"/>天前的订单记录&nbsp;<input type="submit" name="submit" value="立即删除" class="btn btn-sm btn-danger" onclick="return confirm('删除后无法恢复，确定继续吗？');"/-->
</form><br/>
</div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
定期清理数据有助于提升网站访问速度
</div>
</div>
<?php }?>
 </div>
</div>