<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\api\WeChat\PayV3;
use app\api\WeChat\WxPayService;
use app\BaseController;
use think\facade\Db;

class Pay extends BaseController
{
    public function index(): \think\response\Json
    {
        file_put_contents(__DIR__.'/test.txt', json_encode(request()->param()),FILE_APPEND);
        $result = (new PayV3())->payment_notify($input_data);
        if($result['trade_state'] == 'SUCCESS')
        {
            Db::name('hospital_bill')->where('sn', '=', $result['out_trade_no'])->update(['status'=>'padding']);
        }

        if ($result['trade_state'] == 'SUCCESS') {
            $a = array(
                "code" => "SUCCESS",
                "message" => "成功"
            );
        }else{
            $a = array(
                "code" => "ERROR",
                "message" => "失败"
            );
        }

        return $this->_sayOk([]);
    }

    public function finishOrder(): \think\response\Json
    {
        $data = file_get_contents(__DIR__.'/test.txt');
        $input_data = json_decode($data, true);



    }

}
