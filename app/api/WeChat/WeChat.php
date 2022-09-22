<?php
/**
 * 请求中间件
 */

namespace app\api\WeChat;

class WeChat
{
    protected  $mchid = 1631246707;

    protected  $serial='';
    /**
     * 微信开放平台appid
     * @var string
     */
    protected static $kF_AppId = 'wxe192fa80bd2a37f5';

    /**
     * 微信开放平台app secret
     * @var string
     */
    protected static $KF_AppSecret = 'b554a050829f3ceca76f84ecaab4a46d';

    public function getQRCode($userId)
    {
        $file = __DIR__.'/../../../public/storage/wechat/'.$userId.'.jpg';
        if(file_exists($file)){
            return 'http://shop.aenheer.com/'.'storage/wechat/'.$userId.'.jpg';
        }
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=". $token['access_token'];
        $data = [
            "path" => "page/index/index?id=".$userId,
            "width" => 430
        ];

        $res = $this->linkCurl($url, 'POST', $data);
        file_put_contents($file, $res);
        return 'http://shop.aenheer.com/'.'storage/wechat/'.$userId.'.jpg';;
    }

    public function payTransactions()
    {
        $AppId = self::$kF_AppId;
        $AppSecret = self::$KF_AppSecret;
        $url = "https://api.mch.weixin.qq.com/v3/pay/transactions/jsapi";
        $params = [
            'appid' => $AppId,
            'mchid' => '1631246707',
            'description' => '',
            'out_trade_no' => uniqid(),
            'time_expire' => date("c", time() + 3600),
            'notify_url' => 'https://shop.aenheer.com/api/Tokens',
            'amount' => [
                'total' => 3000,
                'currency' => 'CNY'
            ],
            'payer' => [
                'openid' => 'openid'
            ],
        ];
        $authorization = $this->getV3Sign($url, "POST", $param);
        $headers = [
            'Authorization:' . $authorization,
            'Accept:application/json',
            'Content-Type:application/json;charset=utf-8',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
        ];
        $res = $this->linkCurl($url, 'POST', $params, $headers);
        $res = json_decode($res, true);

        return $res;
    }

    private function getV3Sign($url, $http_method, $body)
    {
        $nonce = strtoupper($this->createNonceStr(32));
        $timestamp = time();
        $url_parts = parse_url($url);
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
        $sslKeyPath = __DIR__.'apiclient_key.pem';
        //拼接参数
        $message = $http_method . "\n" .
            $canonical_url . "\n" .
            $timestamp . "\n" .
            $nonce . "\n" .
            $body . "\n";
        $private_key = $this->getPrivateKey($sslKeyPath);
        openssl_sign($message, $raw_sign, $private_key, 'sha256WithRSAEncryption');
        $sign   = base64_encode($raw_sign);
        return sprintf('WECHATPAY2-SHA256-RSA2048 mchid="%s",nonce_str="%s",timestamp="%s",serial_no="%s",signature="%s"', $this->mchid, $nonce, $timestamp, $this->serial, $sign);
    }

    protected function createNonceStr($length = 16) { //生成随机16个字符的字符串
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getPrivateKey()
    {
        $filepath = __DIR__.'apiclient_key.pem';
        return openssl_get_privatekey(file_get_contents($filepath));
    }

    /**
     * 通过开放平台key获取微信登录页面
     * 可通过回调获取code参数
     * @param $callback_url :回调地址
     * @return string
     */
    public function getKFLoginUrl($callback_url)
    {
        $callback = urlencode($callback_url);
        $AppId = self::$kF_AppId;
        $get_code_url = "https://open.weixin.qq.com/connect/qrconnect?appid={$AppId}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        return $get_code_url;
    }

    public function getJscode2session($code)
    {
        $AppId = self::$kF_AppId;
        $AppSecret = self::$KF_AppSecret;
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$AppId}&secret={$AppSecret}&js_code={$code}&grant_type=authorization_code";
        $res = $this->linkCurl($url, 'GET');
        $res = json_decode($res, true);
        return $res;
    }

    public function getAccessToken()
    {
        $AppId = self::$kF_AppId;
        $AppSecret = self::$KF_AppSecret;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$AppId}&secret={$AppSecret}";
        $res = $this->linkCurl($url, 'GET');
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 通过开放平台key
     * 获取用户openId access_token
     * @param $code
     * @return bool|string
     */
    public function getKFOpenId($code)
    {
        $AppId = self::$kF_AppId;
        $AppSecret = self::$KF_AppSecret;
        $get_openid_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$AppId}&secret={$AppSecret}&code={$code}&grant_type=authorization_code";
        $res = file_get_contents($get_openid_url);
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 获取微信用户信息
     * @param $access_token
     * @param $openId
     * @return bool|mixed
     */
    public function getUserInfo($access_token, $openId)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openId}&lang=zh_CN";
        $res = $this->linkCurl($url, 'GET');
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 请求接口返回内容
     * @param $url :请求的URL地址
     * @param $method :请求方式POST|GET
     * @param $params :请求的参数
     * @param $header : 请求头
     * @return bool|string
     */
    protected function linkCurl($url, $method, $params = array(), $header = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (strpos("$" . $url, "https://") == 1) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        } elseif ($params) {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
        }
        $response = curl_exec($ch);
        if ($response === false) {
            return false;
        }
        curl_close($ch);
        return $response;
    }
}
