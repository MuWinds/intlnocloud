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

$password_hash='!@#%!s!0';
//登录判断逻辑  开始
if(isset($_COOKIE["admin_token"])) //后台登陆验证
{
	$token=authcode(daddslashes($_COOKIE['admin_token']), 'DECODE', $conf['KEY']);
	list($user, $sid) = explode("\t", $token);
	$session=md5($conf['admin_user'].$conf['admin_pass'].$password_hash);
	if($session==$sid) {
		$islogin_admin=1;
	}else{
		$islogin_admin='登录不成功';
	}
}
if(isset($_COOKIE["user_token"])) //前台登陆验证
{
	$token=authcode(daddslashes($_COOKIE['user_token']), 'DECODE', $conf['KEY']);
	list($pid, $sid, $expiretime) = explode("\t", $token);
	$userrow=$DB->query("SELECT * FROM pay_user WHERE user='{$pid}' limit 1")->fetch();
	if($userrow and $userrow['pass']){
		$pid = $userrow['user'];
		$key = $userrow['pass'];
	}else{
		$userrow=$DB->query("SELECT * FROM pay_user WHERE pid='{$pid}' limit 1")->fetch();
		$pid = $userrow['pid'];
		$key = $userrow['key'];
	}
	$session=md5($pid.$key.$password_hash);
	if($session==$sid && $expiretime>time()) {
		$pid = $userrow['pid'];
		$key = $userrow['key'];
		$islogin_user=1;
	}else{
		$islogin_user='登录不成功';
	}
}
//登录判断逻辑 结束
?>