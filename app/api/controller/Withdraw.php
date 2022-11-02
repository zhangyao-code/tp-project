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
        $money = $this->getMoney();
        if ($money < $data['amount']) {
            return json(['code' => 100, 'msg' => '提现金额超过能提现的最大值']);
        }
        $data['userId'] = $this->getCurrentUser()['id'];
        $data['status'] = 'normal';
        $data['createdTime'] = time();
        $data['updatedTime'] = time();
        $add_id = Db::name('withdraw')->insertGetId($data);
        $data['id'] = $add_id;

        return json(['code' => 200, 'data' => $data]);
    }


    public function list()
    {
        $data = request()->param();
        $page = isset($data['page']) ? $data['page'] : '1';
        $limit = isset($data['limit']) ? $data['limit'] : '10';

        $where[] = ['userId', '=', $this->getCurrentUser()['id']];
        $userList = Db::name('withdraw')->where($where)->page($page, $limit)->select()->toArray();
        $ajaxarr = ['code' => 200, 'data' => $userList];
        return json($ajaxarr);
    }

    public function getWithdrawAmount(): \think\response\Json
    {
        $ajaxarr = ['code' => 200, 'data' => $this->getMoney()];
        return json($ajaxarr);
    }

    protected function getMoney()
    {
        $count = Db::name('withdraw')
            ->field('sum(amount) as money')
            ->where('userId', '=', $this->getCurrentUser()['id'])
            ->where('status', '<>', 'reject')
            ->select()->toArray();
        $count = empty($count[0]['money']) ? 0 : $count[0]['money'];
        $users = Db::name('retail')
            ->where('parentUserId', '=', $this->getCurrentUser()['id'])->select()->toArray();

        $userIds = empty($users) ? [-1] : array_column($users, 'userId');
        $bills = Db::name('hospital_bill')
            ->field('sum(paymentAmount) as money')
            ->where('userId', 'in', $userIds)
            ->where('status', 'in', ['padding', 'finished'])
            ->select()->toArray();

        $billsCount = empty($bills[0]['money']) ? 0 : $bills[0]['money'];
        $tableData = Db::name('setting')->where(['code' => 'brokerage_Proportion'])->find();
        $billsCount = $billsCount * (float)$tableData['content'];
      
        return $billsCount <= $count ? 0 : $billsCount - $count;
    }

}
