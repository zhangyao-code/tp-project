<?php

namespace app\api\controller;

use app\api\middleware\Check;
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

}
