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
use think\facade\Validate;
use think\facade\View;

class Department extends BaseController
{

    protected $middleware = [Check::class];

    /**
     * 菜单列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            $data = request()->param();
            $page = isset($data['page']) ? $data['page'] : '1';
            $limit = isset($data['limit']) ? $data['limit'] : '10';
            $search = isset($data['search']) ? trim($data['search']) : '';
            $where = [];
            if ($search) {
                $where[] = ['name', 'like', '%' . $search . '%'];
            }
            $where[] = ['status', '=', 'normal'];
            $userList = Db::name('hospital_department')->where($where)->page($page, $limit)->select()->toArray();
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
        if (request()->isAjax()) {
            $data = request()->param();
            $validate = Validate::rule([
                'name|医院名称' => 'require',
            ]);
            if (!$validate->check($data)) {
                return json(['code' => 100, 'msg' => $validate->getError()]);
            }
            if (Db::name('hospital_department')->where(['name' => $data['name']])->find()) {
                return json(['code' => 400, 'msg' => '名称重复，请更换']);
            }
            unset($data['file']);
            $data['createdTime'] = time();
            $data['status'] = 'normal';
            $add_id = Db::name('hospital_department')->insertGetId($data);
            if ($add_id) {
                $ajaxarr = ['code' => 200, 'msg' => '添加成功'];
            } else {
                $ajaxarr = ['code' => 400, 'msg' => '添加失败'];
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
            $validate = Validate::rule([
                'name|医院名称' => 'require',
            ]);
            if (!$validate->check($data)) {
                return json(['code' => 100, 'msg' => $validate->getError()]);

            }
            if (Db::name('hospital_department')->where('id', '<>', $data['id'])->where(['name' => $data['name']])->find()) {
                return json(['code' => 400, 'msg' => '名称重复，请更换']);
            }

            $save_id = Db::name('hospital_department')->save($data);
            if ($save_id) {
                $ajaxarr = ['code' => 200, 'msg' => '编辑成功'];
            } else {
                $ajaxarr = ['code' => 400, 'msg' => '编辑失败'];
            }

            return json($ajaxarr);
        } else {
            $id = isset($data['id']) ? $data['id'] : '';
            $user_data = Db::name('hospital_department')->where(['id' => $id])->find();
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
