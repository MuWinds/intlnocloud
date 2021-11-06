<?php
@header("content-Type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
date_default_timezone_set('PRC');
session_start();
// error_reporting(E_ALL & ~E_NOTICE);
//if(defined('IN_CRONLITE'))return;
define('SYSTEM_ROOT', dirname(__FILE__) . '/');
define('ROOT', dirname(SYSTEM_ROOT) . '/');
define('TEMPLATE_ROOT', ROOT . 'Template/');
define('PAYTEMPLATE_ROOT', ROOT . 'Submit/Template/');
$date = date("Y-m-d H:i:s");
$Version	= 66666; //版本号

if (phpversion() < '5.6') exit('请将php版本调整至5.6以上');
if(function_exists('curl_init') == false) exit('请安装CURL扩展');
if(function_exists('imagepng') == false) exit('请安装GD2扩展');


$scriptpath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sitepath . '/';

if (is_file(SYSTEM_ROOT . '360_Safe/360webscan.php')) { //360网站卫士
	require_once(SYSTEM_ROOT . '360_Safe/360webscan.php');
}

require_once(SYSTEM_ROOT . "Core_Class/Autoloader.php"); //自动载入
Autoloader::register();
include_once(SYSTEM_ROOT . "Core_Class/Security.php");


include(SYSTEM_ROOT . "Config.php");

if (!defined('SQLITE') && (!$dbconfig['user'] || !$dbconfig['pwd'] || !$dbconfig['dbname'])) //检测安装
{
	header('Content-type:text/html;charset=utf-8');
	echo '你还没安装！<a href="/Install/">点此安装</a>';
	exit();
}
try {
	$DB = new PDO("mysql:host={$dbconfig['host']};dbname={$dbconfig['dbname']};port={$dbconfig['port']}", $dbconfig['user'], $dbconfig['pwd']);
} catch (Exception $e) {
	exit('链接数据库失败:' . $e->getMessage());
}
$DB->exec("set names utf8");
require_once SYSTEM_ROOT . 'Core_Class/Cache.Class.php'; //获取系统配置支持库
$CACHE = new CACHE();
$conf = $CACHE->pre_fetch(); //获取系统配置


//此地址作为回调地址 判断是否存在无CDN 如果不存在则默认使用当前发起支付的域名
//if(!$conf['local_domain'])$conf['local_domain']=$_SERVER['HTTP_HOST']; 
$conf['local_domain'] = $_SERVER['HTTP_HOST'];
//require_once(SYSTEM_ROOT."Pay_Class/Mpay/Mpay_core.function.php"); //支付签名组装支持库
//require_once(SYSTEM_ROOT."Pay_Class/Mpay/Mpay_md5.function.php"); //支付签名加密支持库
require_once(SYSTEM_ROOT . "Core_Class/Function.Class.php"); //核心支持库
require_once(SYSTEM_ROOT . "Core_Class/MPay_function.Class.php"); //支付签名组装支持库
include_once(SYSTEM_ROOT . 'Core_Class/Login.Class.php'); //登录支持库
include_once(SYSTEM_ROOT . 'Authcode.php'); //授权码

//云端接口 开始
 include_once(SYSTEM_ROOT . 'Pay_Apis/Pay_Cookie_Api.php');
 $Pay_Cookie_Api = new Pay_Cookie_Api($conf['Instant_url']); //扫码取Cookie	

//Cookie登录取余额
include_once(SYSTEM_ROOT . 'Pay_Apis/Pay_Money_Api.php');
$Pay_Money_Api = new Pay_Money_Api(); //Cookie登录取余额

require_once(SYSTEM_ROOT . "Pay_Apis/Instant_Api.Class.php"); //云端服务器对接支持库

//这个类库我已经修改过了，你放心使用
$Instant_Api=new Instant_Api($conf['Instant_url'],$conf['Instant_pid'],$conf['Instant_key'],$Authcode);
//云端接口 结束


$ip = real_ip();//获取访问者IP地址
	
	// 	if(strlen($Authcode)!=32)
	// 	sysmsg('<h2>你的网站未经授权，购买正版请联系牛马s，享受声誉，安全，程序升级<br/><br/>',true);
	// if(!isset($_SESSION['authcode'])	&&	$islogin_admin==1) {
	// 	$query_A=file_get_contents($conf['Instant_url'].'Api_Check.php?url='.$_SERVER['HTTP_HOST'].'&authcode='.$Authcode);
	// 	$query_A=json_decode($query_A,true);
	// 	$query_B=file_get_contents($Instant_url_list[0].'Api_Check.php?url='.$_SERVER['HTTP_HOST'].'&authcode='.$Authcode);
	// 	$query_B=json_decode($query_B,true);
	// 	if($query_A or $query_B){
	// 		if($query_A['code']==1){
	// 			$_SESSION['authcode']=true;
	// 		}elseif($query_B['code']==1){
	// 			$_SESSION['authcode']=true;
	// 		}elseif($query_A['msg']){
	// 			sysmsg('<h3>'.$query_A['msg'].'</h3>',true);
	// 		}elseif($query_B['msg']){
	// 			sysmsg('<h3>'.$query_B['msg'].'</h3>',true);
	// 		}else{
	// 			sysmsg('<h3>云端链接异常</h3>',true);
	// 		}
	// 	}
	// }
