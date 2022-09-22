<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\api\WeChat\PayV3;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class User extends BaseController
{
    protected $middleware = [Check::class];

    public function index()
    {
        $data = request()->param();
        $validate = Validate::rule([
            'id' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $user_data=Db::name('user')->where(['id'=>$data['id']])->where('status', '<>', 0)->find();

        return $this->_sayOk(['code'=>200,'data'=>$user_data]);
    }


    public function getQRCode()
    {
        $result = (new \app\api\WeChat\WeChat())->getQRCode($this->getCurrentUser()['id']);

        return $this->_sayOk(['code'=>200,'data'=>$result]);

    }

    public function wechartAddOrder()
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $data = request()->param();
        $validate = Validate::rule([
            'billId|订单Id' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $bill = Db::name('hospital_bill')->where('id', '=', $data['billId'])->find();
        $service = Db::name('hospital_service')->where('id', '=', $bill['serviceId'])->find();
        if($bill['status'] != 'normal'){
            return $this->_sayOk(['code'=>100,'data'=>'订单存在问题，不支持支付']);
        }
        $result =  (new PayV3())->wechartAddOrder($service['name'], $bill['sn'],$bill['paymentAmount']*100, $this->getCurrentUser()['username']);

        return $this->_sayOk(['code'=>200,'data'=>$result]);
    }

}
