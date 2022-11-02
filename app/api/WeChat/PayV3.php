<?php
namespace app\api\WeChat;
use think\facade\Db; //引入Db

class PayV3
{
    protected $appid = 'wxe192fa80bd2a37f5';

    protected $mchid = '1631246707';

    protected $serial_number = '65F039A18EAA604B1B6D94A11526B10E0DBB05AA'; //API序列号

    /*
    * 微信支付统一下单
    * @$name 订单名称
    * @$ordernumber 订单单号（自己生成的）
    * @$money  金额
    * @$openid  用户微信的openid
   */
    public function wechartAddOrder($name, $ordernumber, $money, $openid)
    {
        $out_trade_no = uniqid();//生成支付单号（和订单号不一样）这样做是保证同一个订单可以被多次进行支付，因为每次支付单号不一样
        $url = "https://api.mch.weixin.qq.com/v3/pay/transactions/jsapi";
        $urlarr = parse_url($url);
        $data = array();
        $randstr = substr(md5(rand(0,time()).time()),0,16);//随机字符串长度不超过32（加密使用的）
        $time = time();
        $data['appid'] = $this->appid;
        $data['mchid'] = $this->mchid;
        $data['description'] = $name;//商品描述
        $data['attach'] = $ordernumber;//订单编号
        $data['out_trade_no'] = $ordernumber;//支付订单编号
        $data['notify_url'] = "https://shop.aenheer.com/api/Pay";//回调接口
        $data['time_expire'] = date("c", time() + 3600);
        $data['amount']['total'] = $money;
        $data['payer']['openid'] = $openid;//用户付款的openID
        $data = json_encode($data); //转json串
        $key = $this->getSign($data, $urlarr['path'], $randstr, $time);//微信支付签名加密
        $token = sprintf('mchid="%s",serial_no="%s",nonce_str="%s",timestamp="%d",signature="%s"', $this->mchid, $this->serial_number, $randstr, $time, $key);//头部信息
        $header = array(
            'Content-Type:' . 'application/json; charset=UTF-8',
            'Accept:application/json',
            'User-Agent:*/*',
            'Authorization: WECHATPAY2-SHA256-RSA2048 ' . $token
        );
        $ret = $this->curl_post_https($url, $data, $header); //发送请求进行预支付
        $ret = json_decode($ret, true);
        file_put_contents(__DIR__.'/test.txt', $ordernumber.'------'.json_encode($ret).'-------'.json_encode($data),FILE_APPEND);
        if (isset($ret['prepay_id'])) { //拉起预支付成功
            $res['timeStamp'] = "$time";//时间戳
            $res['nonceStr'] = $randstr;//随机字符串
            $res['signType'] = 'RSA';//签名算法，暂支持 RSA
            $res['package'] = 'prepay_id=' . $ret['prepay_id'];//统一下单接口返回的 prepay_id 参数值，提交格式如：prepay_id=*
            $res['paySign'] = $this->getWechartSign($this->appid,$time,$res['nonceStr'],$res['package']);//签名
            return $res; //返回给前端需要的串
        }
        return $ret;
    }

    /*
     * 统一下单之后前端需要的微信支付所需签名
     * 微信支付签名
     * */
    public function getSign($data = array(), $url, $randstr, $time)
    {
        $str = "POST" . "\n" . $url . "\n" . $time . "\n" . $randstr . "\n" . $data . "\n";
        $key = file_get_contents(__DIR__.'/apiclient_key.pem');//在商户平台下载的秘钥
        $str = $this->getSha256WithRSA($str, $key);
        return $str;
    }

    /*
     * 统一下单调起支付的签名
     * */
    public function getWechartSign($appid, $timeStamp, $noncestr, $prepay_id)
    {
        $str = $appid . "\n" . $timeStamp . "\n" . $noncestr . "\n" . $prepay_id . "\n";
        $key = file_get_contents(__DIR__.'/apiclient_key.pem');//在商户平台下载的秘钥
        $str = $this->getSha256WithRSA($str, $key);
        return $str;
    }
    /*
     * 签名方法
     * */
    public function getSha256WithRSA($content, $privateKey)
    {
        $binary_signature = "";
        $algo = "SHA256";
        openssl_sign($content, $binary_signature, $privateKey, $algo);
        $sign = base64_encode($binary_signature);
        return $sign;
    }
    /*
     * POST请求方法
     * PHP CURL HTTPS POST
     * */
    public function curl_post_https($url, $data, $header)
    { // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据，json格式
    }

    /**
     * 申请退款API
     * @$name   订单商品名称
     * @$out_trade_no   订单号
     * @$money   订单退款金额
     * @$transaction_id  微信支付成功的流水单号
     */
    public function refundOrders($name, $out_trade_no, $transaction_id,$money)
    {
        $mchid = env('WECHAT.mch_id');  // 商户号
        $xlid = env('WECHAT.serial_number'); //API序列号
        $url = "https://api.mch.weixin.qq.com/v3/refund/domestic/refunds";
        $urlarr = parse_url($url);
        $data['transaction_id'] = $transaction_id; //原支付交易对应的微信订单号。
        $data['out_refund_no'] = $out_trade_no; //退款单号
        $data['reason'] = "订单". $name ."的退款"; //退款单号
        $data['notify_url'] = ""; //退款回调接口
        $data['amount']['refund'] = $money; //退款金额（只能小于等于支付金额）
        $data['amount']['total'] = $money; //退款金额（只能小于等于支付金额）
        $data['amount']['currency'] = "CNY"; //金额类型
        $randstr = getrandstr(16); //随机字符串长度不超过32
        $time = time();
        $data = json_encode($data);
        $key = $this->getSign($data, $urlarr['path'], $randstr, $time); //签名
        $token = sprintf('mchid="%s",serial_no="%s",nonce_str="%s",timestamp="%d",signature="%s"', $mchid, $xlid, $randstr, $time, $key);//头部信息
        $header = array(
            'Content-Type:' . 'application/json; charset=UTF-8',
            'Accept:application/json',
            'User-Agent:*/*',
            'Authorization: WECHATPAY2-SHA256-RSA2048 ' . $token
        );
        $ret = $this->curl_post_https($url, $data, $header);
        $ret = json_decode($ret, true);
        return $ret;
    }

    /*
    * 微信支付回调
    * */
    public function payment_notify($input_data)
    {
        $key = 'ahb5e944ad83918b8b5e9d88e1912022';//商户平台设置的api v3 密码
        $text = base64_decode($input_data['resource']['ciphertext']); //解密
        /* =========== 使用V3支付需要PHP7.2.6安装sodium扩展才能进行解密参数  ================ */
        $str = sodium_crypto_aead_aes256gcm_decrypt($text,$input_data['resource']['associated_data'], $input_data['resource']['nonce'], $key);
        return json_decode($str, true);
    }

    /*
     * 微信退款回调
     * */
    public function refund_notify($request)
    {
        $input_data = $request->param();
        $key = env('WECHAT.key');//商户平台设置的api v3 密码
        $text = base64_decode($input_data['resource']['ciphertext']);
        /* =========== 使用V3支付需要PHP7.2.6安装sodium扩展才能进行解密参数  ================ */
        $str = sodium_crypto_aead_aes256gcm_decrypt($text, $input_data['resource']['associated_data'], $input_data['resource']['nonce'], $key);
        $res = json_decode($str, true);
        if ($res['refund_status'] == 'SUCCESS') {
            Db::startTrans();
            try {
                /*回调处理逻辑内容*/
                Db::commit();
                $a = array(
                    "code" => "SUCCESS",
                    "message" => "成功"
                );
                return json_encode($a);
            } catch (\Exception $e) {
                Db::rollback();
                $a = array(
                    "code" => "ERROR",
                    "message" => "失败"
                );
                return json_encode($a);
            }
        }
        $a = array(
            "code" => "ERROR",
            "message" => "失败"
        );
        return json_encode($a);
    }
}
