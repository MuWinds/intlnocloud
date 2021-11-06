<?php
// +----------------------------------------------------------------------
// | Quotes [ 只为给用户更好的体验]***[Cookie取微信、财付通、微信余额]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 零度  
// +----------------------------------------------------------------------
// | Date: 2021年03月18日
// +----------------------------------------------------------------------

class Pay_Money_Api
{
	protected $Api_Url 	= null;
	function __construct($Api_Url = 'yun.zhend.top/')
	{
		$this->Api_Url 	= $Api_Url;
	}
	/**
	 * 取余额  类型  Cookie  模拟人工访问其他页面(达到延长Cookie有效时间的效果)
	 */
	function Get_pay_money($Type = 'alipay', $Cookie)
	{
		$Cookie = base64_decode($Cookie);
		if ($Type == 'alipay') {
			switch (rand(1, 9)) {
				case 1:
					$data = $this->Get_Alipay_Cookie('https://personalweb.alipay.com/portal/i.htm', $Cookie);
					break;
				case 2:
					$data = $this->Get_Alipay_Cookie('https://my.alipay.com/wealth/index.html', $Cookie);
					break;
				case 3:
					$data = $this->Get_Alipay_Cookie('https://110.alipay.com/sc/index.htm', $Cookie);
					break;
				case 4:
					$data = $this->Get_Alipay_Cookie('https://my.alipay.com/portal/i.htm', $Cookie);
					break;
				case 5:
					$data = $this->Get_Alipay_Cookie('https://shanghu.alipay.com/home/switchPersonal.htm', $Cookie);
					break;
				case 6:
					$data = $this->Get_Alipay_Cookie('https://cshall.alipay.com/lab/question.htm', $Cookie);
					break;
				case 7:
					$data = $this->Get_Alipay_Cookie('https://cshall.alipay.com/lab/cateQuestion.htm', $Cookie);
					break;
				case 8:
					$data = $this->Get_Alipay_Cookie('https://cshall.alipay.com/lab/help_detail.htm', $Cookie);
					break;
				case 9:
					$data = $this->Get_Alipay_Cookie('https://egg.alipay.com/egg/index.htm', $Cookie);
					break;
				default:
					$data = $this->Get_Alipay_Cookie('http://egg.alipay.com/egg/advice.htm', $Cookie);
					break;
			}
			$preg = '/{\"totalAvailableBalance\":\"(.*?)\",/is';
			preg_match_all($preg, $data, $trStr);
			if (!$trStr[1][0]) {
				$money = "Cookie Time-out";
				$status = false;
			} else {
				$money = $trStr[1][0];
				$status = true;
			}
		} elseif ($Type == 'qqpay') {
			preg_match('/uin\=o(.*?)\;/', $Cookie, $uin);
			$uin = $uin[1];
			$data = get_curl('https://myun.tenpay.com/cgi-bin/clientv1.0/qwallet_record_list.cgi?limit=15&offset=0&s_time=2019-04-20&ref_param=&source_type=7&time_type=0&bill_type=3&uin=' . $uin,0,0,$Cookie);

			$arr = json_decode($data, true);
			if ($arr['retcode'] != '0' && $arr['retmsg'] != 'OK') {
				$money = "Cookie Time-out";
				$status = false;
			} else {
                //获取状态
                $param = $arr['records'][0];
                $money = $param['price'] / 100; //支付金额
				$status = true;
            }
		} elseif ($Type == 'wxpay') {
			switch (rand(1, 3)) {
				case "1":
					$data = $this->Get_Wxpay_Cookie('https://servicewechat.com/wx28be8489b7a36aaa/231/page-frame.html', $Cookie);
					break;
				case '2':
					$data = $this->Get_Wxpay_Cookie('https://servicewechat.com/wx28be8489b7a36aaa/231/page-frame.html', $Cookie);
					break;
				case '3':
					$data = $this->Get_Wxpay_Cookie('https://servicewechat.com/wx28be8489b7a36aaa/231/page-frame.html', $Cookie);
					break;
				default:
					$data = $this->Get_Wxpay_Cookie('https://servicewechat.com/wx28be8489b7a36aaa/231/page-frame.html', $Cookie);
					break;
			}
			$preg = '#income_total_fee":(.*?),"interval"#';
			preg_match_all($preg, $data, $trStr);
			if (!$trStr[1][0] and strstr($data, '登录"')) {
				$money = "Cookie Time-out";
				$status = false;
			} else {
				$money = $trStr[1][0] / 100;
				if ($money == 0) $money = '0.00';
				$status = true;
			}
		} else {
			$money = 0.00;
			$status = false;
		}
		if ($status == 1) { //如果监控不到金额,则COOKIE失效
			$money = str_replace(",", "", $money);
			$money = str_replace(".", "", $money);
			$money = trim(($money / 100));
		}
		return array("status" => $status, "money" => $money, "time" => time(), "cookie" => $Cookie);
	}


	//支付宝
	protected  function Get_Alipay_Cookie($Url_Referer, $Cookie = null)
	{
		$ctoken = $this->getSubstr($Cookie, "ctoken=", ";");
		$Url = 'https://mrchportalweb.alipay.com/user/asset/queryData?_ksTS=' . time() . '_' . rand(10, 99) . '&_input_charset=utf-8&ctoken=' . $ctoken;
		$referer = $Url_Referer . '?&t=' . time();
		$ua = ':authority:' . $referer . '
:method: GET
:path: /
:scheme: https
accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8
accept-encoding: gzip, deflate, br
accept-language: zh-CN,zh;q=0.9
cache-control: max-age=0
cookie: ' . $Cookie . '
referer: ' . $Url . '?referer=' . $referer . '
upgrade-insecure-requests: 1
user-agent: Mozilla/5.0 (Linux; Android 10.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36 360Browser/9.2.5584.400';
		$result = $this->Get_Money_curl($Url_Referer, null, 0, $Cookie, 0, $ua);
		$result = $this->Get_Money_curl($Url, 0, $referer, $Cookie, 0, $ua);
		return $result;
	}
	//QQ钱包
	protected  function Get_Qqpay_Cookie($Url_Referer = 0, $Cookie = null)
	{
		//$Url = 'https://www.tenpay.com/app/v1.0/miniaccount.cgi?OutPutType=JSONP&JsonObj=BC_Call_Back&tid=1&r='.time().'&g_tk=202895705';
		$uin = explode("qluin=", $Cookie);
		$skey = $this->getSubstr($Cookie, "skey=", ";");
		$Url = 'https://myun.tenpay.com/cgi-bin/clientv1.0/qwallet_account_list.cgi?limit=10&offset=0&s_time=' . date("Y-m-d") . '&time_type=0&source_type=7&pay_type=2&ref_param=&skey=' . $skey . '&skey_type=2&uin=' . $uin[1];
		$referer = $Url_Referer . '?&t=' . time();
		$ua = 'authority:' . $referer . '
	               method:GET
	               path:' . $Url . '?referer=' . $referer . '
	               scheme:https
				   user-agent: Mozilla/5.0 (Linux; Android 10.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36 QQBrowser/9.2.5584.400';
		//$result = $this->Get_Money_curl($referer,0,$referer,$Cookie,0,$ua);
		$result = $this->Get_Money_curl($Url, 0, $referer, $Cookie, 0, $ua);
		return $result;
	}

	//微信
	protected  function Get_Wxpay_Cookie($Url_Referer, $Cookie = null)
	{

		$Url = 'https://payapp.weixin.qq.com/qrappzd/user/gethomedata?sid=' . $Cookie . '&v=4.14.0';
		$post = '{"type":2,"sid":"' . $Cookie . '","start_time":' . time() . ',"v":"4.14.0","end_time":' . (time() + 2109877665) . '}';
		$referer = $Url_Referer . '&t=' . time();
		$ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36';
		$header = ':authority:lab.alipay.com
	               :method:GET
	               :path:/user/navigate.htm?referer=https%3A%2F%2Fauth.alipay.com%2Flogin%2FhomeB.htm%3FredirectType%3Dparent
	               :scheme:https
	               accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8
	               accept-encoding:gzip, deflate, br
	               accept-language:zh-CN,zh;q=0.9';
		//$result = $this->Get_Money_curl($Url_Referer,0,$referer,$Cookie,$header,$ua);
		//$result = $this->Get_Money_curl($Url,$post,$referer,$Cookie,$header,$ua);
		return $result;
	}

	//取源代码
	protected  function Get_Money_curl($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobaody = 0)
	{
		if (is_array($cookie)) {
			$str = '';
			foreach ($cookie as $key => $value) {
				$str .= $key . '=' . $value . '; ';
			}
			$cookie = substr($str, 0, -1);
		}
		$opts = array(
			'http' => array(
				'method' => ($post ? 'POST' : 'GET'),
				'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
					"Content-length:" . strlen($post) . "\r\n" .
					"Cookie: " . @$cookie . "\r\n" .
					"\r\n" . $ua .
					"\r\n",
				'content' => $post,
			)
		);
		$context = stream_context_create($opts);
		$ret = file_get_contents($url, false, $context);
		return $ret;
	}
	protected  function getSubstr($str, $leftStr, $rightStr)
	{
		$left = strpos($str, $leftStr);
		//echo '左边:'.$left;
		$right = strpos($str, $rightStr, $left);
		//echo '<br>右边:'.$right;
		if ($left < 0 or $right < $left) return '';
		return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
	}
}
