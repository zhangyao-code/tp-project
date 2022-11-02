<?php

/**
 * 账户管理
 * 开发者：浮生若梦
 * 开发时间：2020/9/7
 */

namespace app\admin\controller;

use app\admin\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;

class Withdraw extends BaseController
{
    protected $middleware = [Check::class];

    protected $status = [
        'normal'=>'等待审核',
        'reject'=>'拒绝',
        'agree'=>'同意',
    ];
    /**
     * 菜单列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            $data = request()->param();
            $page=isset($data['page']) ? $data['page'] : '1';
            $limit=isset($data['limit']) ? $data['limit'] : '10';
            $search=isset($data['search']) ? trim($data['search']) : '';
            $where=[];
            $userList = Db::name('withdraw')->where($where)->order('status ASC')->page($page, $limit)->select()->toArray();
            $userIds = array_filter(array_unique(array_column($userList, 'userId')));
            $users = empty($userIds) ? [] : Db::name('user')->where('id', 'in', $userIds)->select()->toArray();
            $count = empty($userIds) ? 0 : Db::name('user')->where('id', 'in', $userIds)->count('id');
            $users = array_column($users, null, 'id');
            foreach ($userList as $k => $vo) {
                $userList[$k]['user'] = $users[$vo['userId']]['truename'];
                $userList[$k]['statusX'] = $this->status[$vo['status']];
                $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
                $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            }
            $ajaxarr=['code'=>0,'data'=>$userList, 'count'=>$count];
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

    /**
     * 添加菜单
     */
    public function change()
    {
        if (request()->isAjax()) {
            $data=request()->param();
            $validate = Validate::rule([
                'id|id' => 'require',
                'status|操作' => 'require',
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                
                Db::name('withdraw')->save($data);

                $ajaxarr=['code'=>200,'msg'=>'修改成功'];
            }
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

}
