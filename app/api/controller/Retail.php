<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class Retail extends BaseController
{
    protected $middleware = [Check::class];

    public function index()
    {
        $data = request()->param();
        $page = isset($data['page']) ? $data['page'] : '1';
        $limit = isset($data['limit']) ? $data['limit'] : '10';

        $userList = Db::name('retail')->where('parentUserId', '=', $this->getCurrentUser()['id'])->page(0, 10000)->select()->toArray();
        $where[] = ['userId', 'in', empty($userList) ? [-1] :array_column($userList, 'userId')];
        $search = isset($data['finished']) ? $data['finished'] : 0;
        if ($search) {
            $where[] = ['status', '=','finished'];
        }else{
            $where[] = ['status', 'in',['padding', 'normal']];
        }

        $userList = Db::name('hospital_bill')->where($where)->page($page, $limit)->select()->toArray();
        $total = Db::name('hospital_bill')->where($where)->page($page, $limit)->count('id');
        foreach ($userList as $k => $vo) {
            $userList[$k]['treatmentTime'] = date('Y-m-d H:i:s', $vo['treatmentTime']);
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            $userList[$k]['hospital'] =  Db::name('hospital')->where('id', '=', $vo['hospitalId'])->find();
            $userList[$k]['service'] =  Db::name('hospital_service')->where('id', '=', $vo['serviceId'])->find();
            $userList[$k]['patient'] =  Db::name('patient')->where('id', '=', $vo['patientId'])->find();
        }
        $ajaxarr = ['code' => 0, 'total' => $total, 'data' => $userList];
        return json($ajaxarr);
    }
}
