<?php

namespace app\api\controller;

use app\BaseController;
use think\facade\Validate;

class WeChat extends BaseController
{
    public function index()
    {
        $data = request()->param();
        $validate = Validate::rule([
            'code|登陆code' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $data = (new \app\api\WeChat\WeChat())->getJscode2session($data['code']);
        file_put_contents('/var/www/test-we.log', json_encode($data), true);
        return $this->_sayOk(['code'=>200,'data'=>$data]);
    }
}
