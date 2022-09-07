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

class Hospital extends BaseController
{
    protected $tags = ['三甲', '综合医院'];

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
            $where[] = ['deleted', '=', 0];
            $userList = Db::name('hospital')->where($where)->page($page, $limit)->select()->toArray();

            foreach ($userList as $k => $vo) {
                $departments = empty($vo['departments']) ? []: Db::name('hospital_department')->whereIn('id', explode(',',$vo['departments']))->select()->toArray();
                $departments = array_column($departments, 'name');
                $userList[$k]['departments'] = $departments;
                $userList[$k]['tags'] = array_filter(explode(',',$vo['tags']));
                $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
                $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
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
                'img|医院图片' => 'require',
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                if (Db::name('hospital')->where(['name' => $data['name']])->find()) {
                    $ajaxarr = ['code' => 400, 'msg' => '医院名称重复，请更换'];
                } else {
                    unset($data['file']);
                    $data['createdTime'] = time();
                    $data['updatedTime'] = time();
                    $add_id = Db::name('hospital')->insertGetId($data);
                    if ($add_id) {
                        $ajaxarr = ['code' => 200, 'msg' => '添加成功'];
                    } else {
                        $ajaxarr = ['code' => 400, 'msg' => '添加失败'];
                    }
                }
            }
            return json($ajaxarr);
        } else {
            $departments = Db::name('hospital_department')->where('status', '=', 'normal')->select()->toArray();
            View::assign('tags', $this->tags);
            View::assign('departments', $departments);
            return View::fetch();
        }
    }

    /**
     * 上传
     */
    public function upload()
    {
        $file = request()->file('file');
        if (!Validate::fileSize($file, 1024 * 1024 * 5)) {
            $ajaxarr = ['code' => 400, 'msg' => '图片过大'];
        } else if (!Validate::fileExt($file, 'jpeg,jpg,png,gif')) {
            $ajaxarr = ['code' => 400, 'msg' => '图片格式错误'];
        } else {
            $info = Filesystem::disk('public')->putFile('upload/hospital', $file);
            $img_path = '/storage/' . $info;
            $ajaxarr = ['code' => 0, 'data' => ['src' => $img_path]];
        }
        return json($ajaxarr);
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
                'img|医院图片' => 'require',
            ]);
            if (!$validate->check($data)) {
                return json(['code' => 100, 'msg' => $validate->getError()]);
            }
            if (Db::name('hospital')->where('id', '<>', $data['id'])->where(['name' => $data['name']])->find()) {
                return json(['code' => 400, 'msg' => '名称重复，请更换']);
            }
            unset($data['file']);
            $data['updatedTime'] = time();
            $save_id = Db::name('hospital')->save($data);
            if ($save_id) {
                $ajaxarr = ['code' => 200, 'msg' => '编辑成功'];
            } else {
                $ajaxarr = ['code' => 400, 'msg' => '编辑失败'];
            }


            return json($ajaxarr);
        } else {
            $id = isset($data['id']) ? $data['id'] : '';
            $user_data = Db::name('hospital')->where(['id' => $id])->find();
            $user_data['tags'] = explode(',', $user_data['tags']);
            $user_data['departments'] = explode(',', $user_data['departments']);
            View::assign('hospital_data', $user_data);
            View::assign('tags', $this->tags);
            $departments = Db::name('hospital_department')->where('status', '=', 'normal')->select()->toArray();
            View::assign('departments', $departments);
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
