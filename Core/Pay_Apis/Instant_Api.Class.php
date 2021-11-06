<?php 
// +----------------------------------------------------------------------
// | Quotes [ 只为给用户更好的体验]***[即时到账云端连接函数Api]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 零度  
// +----------------------------------------------------------------------
// | Date: 2018年01月24日
// +----------------------------------------------------------------------

//引用服务器URL列表文件
require_once('Instant_Url_List.php');

require_once('TenpayHttpClient.class.php');//通讯引用文件

class Instant_Api {
    protected $Instant_url 	= null;
    protected $Instant_pid = null;
    protected $Instant_key = null;
    function __construct($Instant_url = 'http://127.0.0.1/',$Instant_pid = null,$Instant_key = null,$Authcode = null)
    {
        $this->Apikey	= 1234567890;//接口对接密钥
        $this->Instant_url 	= $Instant_url;
        $this->Instant_pid  = $Instant_pid;
        $this->Instant_key  = $Instant_key;
        $this->Authcode     = $Authcode;
		
		//老子咋说响应这么慢
		// //通信对象
        // $this->httpClient  = new TenpayHttpClient();
		// $this->httpClient->setTimeOut(5);//超时时间
    }
	
    
    /**
     * 二维码添加并解码    
	 * 提交参数:二维码URL
     */
    function Add_Qrcode($file = null,$pid = null)
    {
		return true;

    } 
	
    /**
     * 向云端提交回调数据     
	 * 提交参数:类型,收款金额
     */
    function Get_Money_Notify($api_type,$money)
    {
		// $Arrs = array("act" =>'Get_Money_Notify',"apikey" =>$this->Apikey,"url" =>$_SERVER['HTTP_HOST'],"authcode" =>$this->Authcode,"Instant_pid" =>$this->Instant_pid,"Instant_key" =>$this->Instant_key,"api_type" =>$api_type,"money" =>$money);
		// //设置请求内容
		// $this->httpClient->setReqContent($this->Instant_url.'Api_Instant.php?'.http_build_query($Arrs));
		// //后台调用
		// if($this->httpClient->call()) {
		// 	//设置结果参数
		// 	$json = json_decode($this->httpClient->getResContent(),true);
		// }
		// return $json;
		return true;
	}

	
    /**
     * 获取即时到帐云端资料
	 * 提交参数:Instant_pid,Instant_key
     */
    function Query($Instant_pid = null,$Instant_key = null)
    {
		// if($Instant_pid and $Instant_key){
		// 	$Arrs = array("act" =>'Query',"apikey" =>$this->Apikey,"url" =>$_SERVER['HTTP_HOST'],"authcode" =>$this->Authcode,"Instant_pid" =>$Instant_pid,"Instant_key" =>$Instant_key);
		// }else{
		// 	$Arrs = array("act" =>'Query',"apikey" =>$this->Apikey,"url" =>$_SERVER['HTTP_HOST'],"authcode" =>$this->Authcode,"Instant_pid" =>$this->Instant_pid,"Instant_key" =>$this->Instant_key);
		// }
		// //设置请求内容
		// $this->httpClient->setReqContent($this->Instant_url.'Api_Instant.php?'.http_build_query($Arrs));
		// //后台调用
		// if($this->httpClient->call()) {
		// 	//设置结果参数
		// 	$json = json_decode($this->httpClient->getResContent(),true);
		// }
		// return $json;
		return true;
	}
	
    /**
     * 记录订单
	 * 提交参数:用户ID,付款超时 单位：秒,云支付订单号,商户订单号,异步通知地址,支付方式,名称,金额
     */
    function Submit($pay_id,$outtime,$trade_no,$out_trade_no,$notify_url,$type,$name,$money,$sign)
    {
        // $Arrs = array("act" =>'Submit',"apikey" =>$this->Apikey,"url" =>$_SERVER['HTTP_HOST'],"authcode" =>$this->Authcode,"Instant_pid" =>$this->Instant_pid,"Instant_key" =>$this->Instant_key,"pay_id" =>$pay_id,"outtime" =>$outtime,"trade_no" =>$trade_no,"out_trade_no" =>$out_trade_no,"notify_url" =>$notify_url,"type" =>$type,"name" =>$name,"money" =>$money,"sign" =>$sign);
		// //设置请求内容
        // $this->httpClient->setReqContent($this->Instant_url.'Api_Instant.php?'.http_build_query($Arrs));
		// //后台调用
		// if($this->httpClient->call()) {
		// 	//设置结果参数
		// 	$json = json_decode($this->httpClient->getResContent(),true);
		// }
		// return $json;			
		return true;
    }  
	
    protected  function Request_by_curl($url,$file,$pid)
    {
        // //$file = dirname(__FILE__).'/1.png';
		// $img = new CurlFile($file);
		// // $data = array("act" =>'Add_Qrcode',"apikey" =>$this->Apikey,"url" =>$_SERVER['HTTP_HOST'],"authcode" =>$this->Authcode,"Instant_pid" =>$this->Instant_pid,"Instant_key" =>$this->Instant_key,"img" =>$img,"pid" =>$pid);
		// $ch = curl_init();
		// curl_setopt($ch,CURLOPT_URL, $url);
		// curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		// curl_setopt($ch,CURLOPT_POST,true);
		// // curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		// $result = curl_exec($ch);
		// curl_close($ch);
        // return $result;
    }


	protected static  function curl($url,$post='',$cookies='',$header='',$referer='',$ip=0,$heads = 0) {
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
		if($heads){
			curl_setopt($curl, CURLOPT_HEADER, TRUE);
		}
		if($ip==1){
		// $proxyServer = "http://tps143.kdlapi.com:15818";
		// $proxyUser = "t12142526692539";
		// $proxyPass = "f34co1il";
		
		// curl_setopt($curl, CURLOPT_PROXYTYPE, 0);
		// curl_setopt($curl, CURLOPT_PROXY, $proxyServer);
		// curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
		// curl_setopt($curl, CURLOPT_PROXYUSERPWD,"{$proxyUser}:{$proxyPass}");
		}
		if($post!=''){
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		}
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
		curl_setopt($curl, CURLOPT_REFERER,$referer);
			  curl_setopt($curl, CURLOPT_COOKIE, $cookies);
			  if($header){
		   curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		}
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		return $data;
		curl_close($curl);

	}

}
?>