<?php
// +----------------------------------------------------------------------
// | Quotes [ 只为给用户更好的体验]***[Cookie取微信、财付通、微信余额]
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 零度  
// +----------------------------------------------------------------------
// | Date: 2018年01月24日
// +----------------------------------------------------------------------

class Pay_Cookie_Api
{
    function __construct($Api_Url = 'http://127.0.0.1/')
    {
        // $this->Api_Url     = $Api_Url;
    }


    /**
     * 提交验证码
     */
    function Get_Code($id, $code = null)
    {
        // $data = $this->Ck_get_curl($this->Api_Url . 'Api_LoginQeCode.php?act=Get_Code&id=' . $id . '&code=' . urlencode($code));
        // $json = json_decode($data, true);
        // if ($json['id'] and $json['qr_url']) {
        //     $return = array("code" => 1, "msg" => $json['msg'], "id" => $json['id']);
        // } elseif ($json['id']) {
        //     $return = array("code" => 1, "msg" => $json['msg'], "id" => $json['id']);
        // } elseif ($json['msg']) {
        //     $return = array("code" => -1, "msg" => $json['msg'], "id" => false);
        // } else {
        //     $return = array("code" => -1, "msg" => '->云端未响应', "id" => false);
        // }
        // return $return;
        return true;
    }

    /**
     * 提交取登录二维码请求
     */

    //他这个获取二维码改起来比较麻烦，我们就通过Curl进行转发
    function Get_Login_QrCode($Type = 'alipay', $wx_name = null, $pay_user = null, $pay_pass = null)
    {
        require __DIR__ . '/../../Config.php';
        if ($Type == 'alipay') {
            // $response = $this->Ck_get_curl('http://'.$_SERVER['HTTP_HOST'].'/Core/GetCookie/alipay.php?act=getqrcode');
            $response = $this->Ck_get_curl($GetCookie['alipay'] . '?act=getqrcode');
            $query = json_decode($response, true);
            if ($query['code'] == 1) {
                // print_r($query);
                $return = array("code" => 1, "msg" => '获取成功', "id" => $query['loginid'], "qr_url" => '/User/ewm/?text=' . $query['qrcodeurl']);
            } else {
                $return = array("code" => -1, "msg" => '获取失败', "id" => false);
            }

            return $return;
        } else {
            //这里是 qq财付通
            $response = $this->Ck_get_curl($GetCookie['tenpay'] . '?do=getqrpic');
            $query = json_decode($response, true);
            if ($query['code'] == 1) {
                // print_r($query);
                $return = array("code" => 1, "msg" => '获取成功', "id" => $query['qrsig'], "qr_url" => 'data:image/png;base64,' . $query['data']);
            } else {
                $return = array("code" => -1, "msg" => '获取失败', "id" => false);
            }

            return $return;
        }
    }

    /**
     * 取登录二维码
     */
    function Get_Wx_QrUrl($Id = '1000')
    {
        // $data = $this->Ck_get_curl($this->Api_Url . 'Api_LoginQeCode.php?act=Get_Wx_QrUrl&id=' . $Id);
        // $json = json_decode($data, true);
        // if ($json['qr_url']) {
        //     $return = array("code" => 1, "msg" => $json['msg'], "id" => $json['id'], "qr_url" => $json['qr_url']);
        // } elseif ($json['code'] == 1) {
        //     $return = array("code" => 1, "msg" => $json['msg'], "id" => $json['id'], "qr_url" => false);
        // } else {
        //     $return = array("code" => -1, "msg" => $json['msg'], "id" => false, "qr_url" => false);
        // }
        // return $return;
        return true;
    }

    /**
     * 取登录Cookie  返回64位加密
     */
    function Get_Login_Cookie($Id = '1000')
    {
        require __DIR__ . '/../../Config.php';

        //支付宝
        if (strlen($Id) > 10) {
            //财付通的

            $response = $this->Ck_get_curl($GetCookie['tenpay'] . '?do=qrlogin&qrsig=' . $Id);
            $query = json_decode($response, true);
            if ($query['code'] == 1) {
                // print_r($query);

                $return = array("code" => 1, "msg" => $query['message'], "id" => $Id, "cookie" => $query['cookie'], 'data' => json_encode($query));
            } else {
                $return = array("code" => 1, "msg" => $query['message'], "id" => false, "cookie" => false);
            }
        } else {
            //支付宝的
            $response = $this->Ck_get_curl($GetCookie['alipay'] . '?act=getcookie&loginid=' . $Id);
            $query = json_decode($response, true);
            if ($query['code'] == 1) {
                // print_r($query);
                $return = array("code" => 1, "msg" => $query['msg'], "id" => $Id, "cookie" => $query['cookie']);
            } else {
                $return = array("code" => 1, "msg" => $query['msg'], "id" => false, "cookie" => false);
            }
        }
        return $return;
    }

    protected  function Ck_get_curl($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobaody = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $klsf[] = "Accept:*";
        $klsf[] = "Accept-Encoding:gzip,deflate,sdch";
        $klsf[] = "Accept-Language:zh-CN,zh;q=0.8";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $klsf);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
        }
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        if ($referer) {
            if ($referer == 1) {
                curl_setopt($ch, CURLOPT_REFERER, "http://m.qzone.com/infocenter?g_f=");
            } else {
                curl_setopt($ch, CURLOPT_REFERER, $referer);
            }
        }
        if ($ua) {
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        } else {
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 4.4.1; zh-cn; R815T Build/JOP40D) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1');
        }
        if ($nobaody) {
            curl_setopt($ch, CURLOPT_NOBODY, 1); //主要头部
            //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);//跟随重定向
        }
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
}
