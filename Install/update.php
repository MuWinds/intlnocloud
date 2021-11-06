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

$update		=	'update';
require_once('../Core/Common.php');
@header('Content-Type: text/html; charset=UTF-8');
if( $conf['version'] == $Version ) {
echo "<script language='javascript'>alert('您的数据库信息已是最新版:{$conf['version']},无需更新！');window.location.href='/Admin/Update.php';</script>";
}else{
			$version_a = 2068;//此处填写最新版本的上版本号
			$version_b = 2067;//此处填写最新版本的上上版本号
			$version_c = 2066;//此处填写最新版本的上上上版本
	
			if($conf['version']==$version_a){//此处填写最新版本的上版本号
				
				$Version	=	$Version;
			
			}elseif($conf['version']==$version_b){	//此处填写最新版本的上上版本号
			
				$Version	=	$version_a;//此处填写最新版本的上版本号
			
			}elseif($conf['version']==$version_c){//此处填写最新版本的上上上版本
				
				$Version	=	$version_b;//此处填写最新版本的上上版本号
				
			}else{
				
				$Version	=	$Version;//开始更新最新版本
				
			}
	
/*
$sqls = file_get_contents($Version.'.sql');
$explode = explode(';', $sqls);
$num = count($explode);
foreach($explode as $sql) {
	//if($sql)$DB->query($sql);
	if($sql)$DB->prepare($sql);
}	
*/
require './db.class.php';
$sqls = file_get_contents($Version.'.sql');
$sql=explode(';',$sqls);
	$cn = DB::connect($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'],$dbconfig['dbname'],$dbconfig['port']);
	if (!$cn) die('err:'.DB::connect_error());
	DB::query("set sql_mode = ''");
	DB::query("set names utf8");
	$t=0; $e=0; $error='';
	for($i=0;$i<count($sql);$i++) {
		if ($sql[$i]=='')continue;
		if(DB::query($sql[$i])) {
			++$t;
		} else {
			++$e;
			$error.=DB::error().'<br/>';
		}
	}
saveSetting('version',$Version);
$CACHE->clear();
exit("<script language='javascript'>alert('网站数据库升级完成,已经更新到{$Version}版的数据！');window.location.href='/Admin/Update.php';</script>");
}
?>