<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;

class Coupon extends BaseController
{
    protected $middleware = [Check::class];

    public function index(): \think\response\Json
    {
        $where=[];
        $where[] = ['status','<>', 'delete'];
        $where[] = ['userId','=', $this->getCurrentUser()['id']];
        $userList = Db::name('coupon')->where($where)->select()->toArray();
        foreach ($userList as $k => $vo) {
            unset($userList[$k]['parentId']);
            unset($userList[$k]['userId']);
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
        }
        $ajaxarr=['code'=>0,'data'=>$userList];
        return json($ajaxarr);
    }
}
