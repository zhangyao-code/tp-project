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

     $result =  (new PayV3())->wechartAddOrder('ddd', uniqid(),20, 'dfasdfasd');
      return $this->_sayOk($result);
    }


}
