<?php
class Oauth
{
    function __construct()
    {
        global $siteurl;
        $this->callback = $siteurl . 'User/Social.php';//登录回调地址
    }
    public function login()
    {
        global $allapi;
		
		//-------生成唯一随机串防CSRF攻击
        $state = md5(uniqid(rand(), TRUE));
		
		setcookie("Oauth_state",$state,time()+600,'/');
		
        $keysArr = array("redirect_uri" => $this->callback, "state" => $state);
		
        $login_url = $allapi . 'qqlogin/qqlogin.php?' . http_build_query($keysArr);
		
		header("Location:{$login_url}");
		
	    }
    public function callback()
    {
        global $allapi;
		
		//--------验证state防止CSRF攻击
		if ($_GET['state'] != $_COOKIE['Oauth_state']) {
			
         echo"<h2>The state does not match. You may be a victim of CSRF.</h2>";
		 
		}else{
			
        $keysArr = array("code" =>$_GET['code'], "state" =>$_COOKIE['Oauth_state'], "key" =>"zero2109877665");
		
        $token_url = $allapi . 'qqlogin/qqlogin.php?' . http_build_query($keysArr);
		
        $response = $this->curl_get($token_url);
		
        $arr = json_decode($response, true);
		
        if ($arr['code']!=1) {
			
         echo'<h3>msg  :</h3>' . $arr['msg'];
        }		
        return $arr;
		}
    }
	public function curl_get($url)
	{
	$ch=curl_init($url);
	$httpheader[] = "Accept: */*";
	$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
	$httpheader[] = "Connection: close";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 4.4.1; zh-cn; R815T Build/JOP40D) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1');
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$content=curl_exec($ch);
	curl_close($ch);
	return($content);
	}
}