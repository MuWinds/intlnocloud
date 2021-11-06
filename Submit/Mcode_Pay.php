<?php
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    die('require PHP > 5.6 !');
}
include("../Core/Common.php");
$trade_no=daddslashes($_GET['trade_no']);
$sitename=base64_decode(daddslashes($_GET['sitename']));
$srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$trade_no}' limit 1")->fetch();
if(!$srow)sysmsg('该订单号不存在，请返回来源地重新发起请求！');
$userrow=$DB->query("SELECT * FROM pay_user WHERE pid='{$srow['pid']}' limit 1")->fetch();
$mod = isset($_GET['mod'])?$_GET['mod']:'INTL_Pay';
$loadfile = \lib\PayTemplate::load($mod);
include $loadfile;