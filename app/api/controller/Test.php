<?php

namespace app\api\controller;

use app\api\WeChat\WeChat;
use app\BaseController;

class Test extends BaseController
{
    public function index()
    {
        $result = (new WeChat())->getKFLoginUrl('http://121.199.53.111/api/course');
        var_dump($result);
        return $this->_sayOk([]);
    }
}
