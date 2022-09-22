<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class Withdraw extends BaseController
{
    protected $middleware = [Check::class];

    public function save(): \think\response\Json
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $validate = Validate::rule([
            'amount|提现金额' => 'require',
            'username|姓名' => 'require',
            'bankCard|银行卡号' => 'require',
            'bankDetail|开户行' => 'require',
        ]);
        $data = request()->param();
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $data['userId'] = $this->getCurrentUser()['id'];
        $data['status'] = 'normal';
        $data['createdTime'] = time();
        $data['updatedTime'] = time();
        $add_id = Db::name('withdraw')->insertGetId($data);
        $data['id'] = $add_id;

        return json(['code'=>200,'data'=>$data]);
    }


    public function list()
    {
        $data = request()->param();
        $page = isset($data['page']) ? $data['page'] : '1';
        $limit = isset($data['limit']) ? $data['limit'] : '10';

        $where[] = ['userId', '=',$this->getCurrentUser()['id']];
        $userList = Db::name('withdraw')->where($where)->page($page, $limit)->select()->toArray();
        $ajaxarr = ['code' => 200, 'data' => $userList];
        return json($ajaxarr);
    }

    public function getWithdrawAmount(): \think\response\Json
    {
        $name = Db::name('withdraw')->getTable();
        $sql="SELECT sum(amount) as amount FROM {$name} where userId = {$this->getCurrentUser()['id']}";
        $result = Db::query($sql);
       var_dump($result);
        $ajaxarr = ['code' => 200, 'data' => 1000];
        return json($ajaxarr);
    }

}
