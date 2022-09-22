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
     file_put_contents(__DIR__.'/test.txt', json_encode(request()->param()));
      return $this->_sayOk([]);
    }


}
