<?php

/**
 * 账户管理
 * 开发者：浮生若梦
 * 开发时间：2020/9/7
 */

namespace app\admin\controller;

use app\admin\middleware\Check;
use app\BaseController;
use think\Exception;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;

class Config extends BaseController
{
    protected $tags = ['三甲', '综合医院'];

    protected $middleware = [Check::class];

    /**
     * 菜单列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            $where[] = ['hide', '=', 0];
            $userList = Db::name('setting')->where($where)->select()->toArray();

            foreach ($userList as $k => $vo) {
                $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            }
            $ajaxarr = ['code' => 0, 'data' => $userList];
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

    /**
     * 添加菜单
     */
    public function add()
    {
        $data = request()->param();
        if (request()->isAjax()) {
            $tableData = Db::name('setting')->where(['code' => $data['code']])->find();
            if (empty($tableData)) {
                $add_id = Db::name('setting')->insertGetId([
                        'code'=>$data['code'],
                        'content' => $data['setting'],
                        'createdTime' => time(),
                        'summary' =>  $data['summary'],
                    ]);
            } else {
                $tableData['content'] = $data['setting'];
                $tableData['summary'] = $data['summary'];
                $add_id =   Db::name('setting')->save($tableData);
            }
            if ($add_id) {
                $ajaxarr = ['code' => 200, 'msg' => '设置成功'];
            } else {
                $ajaxarr = ['code' => 400, 'msg' => '设置失败'];
            }
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

    /**
     * 菜单编辑
     */
    public function edit()
    {
        $data = request()->param();
        if (request()->isAjax()) {
            $tableData = Db::name('setting')->where(['code' => $data['code']])->find();
            if (empty($tableData)) {
                $add_id = Db::name('setting')->insertGetId([
                    'code'=>$data['code'],
                    'content' => $data['setting'],
                    'createdTime' => time(),
                    'summary' =>  $data['summary'],
                ]);
            } else {
                $tableData['content'] = $data['setting'];
                $tableData['summary'] = $data['summary'];
                $add_id =   Db::name('setting')->save($tableData);
            }
            if ($add_id) {
                $ajaxarr = ['code' => 200, 'msg' => '设置成功'];
            } else {
                $ajaxarr = ['code' => 400, 'msg' => '设置失败'];
            }
            return json($ajaxarr);
        } else {
            $id = isset($data['id']) ? $data['id'] : '';
            $user_data = Db::name('setting')->where(['id' => $id])->find();
            View::assign('data', $user_data);
            return View::fetch();
        }
    }

    /**
     * 菜单删除
     */
    public function delete()
    {
        $data = request()->param();
        $validate = Validate::rule([
            'id|医院ID' => 'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id = Db::name('hospital')->where(['id' => $data['id']])->save(['deleted' => 1, 'updatedTime' => time()]);
            if ($save_id) {
                $ajaxarr = ['code' => 200, 'msg' => '账户删除成功'];
            } else {
                $ajaxarr = ['code' => 400, 'msg' => '账户删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
