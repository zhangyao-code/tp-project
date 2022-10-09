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

class About extends BaseController
{
    protected $middleware = [Check::class];
    /**
     * 菜单列表
     */
    public function index()
    {
        $tableData = Db::name('setting')->where(['code' => 'anheAbout'])->find();

        if (request()->isAjax()) {
            $data = request()->param();
            $data['setting'] = empty($data['setting']) ? '' : $data['setting'];
            if (empty($tableData)) {
                $add_id = Db::name('setting')->insertGetId([
                    'code'=>'anheAbout',
                    'content' => $data['setting'],
                    'createdTime' => time(),
                    'hide'=>1
                ]);
            } else {
                $tableData['content'] = $data['setting'];
                $add_id =   Db::name('setting')->save($tableData);
            }
            if ($add_id) {
                $ajaxarr = ['code' => 200, 'msg' => '设置成功'];
            } else {
                $ajaxarr = ['code' => 400, 'msg' => '设置失败'];
            }

            return json($ajaxarr);
        } else {
            View::assign('setting', $tableData);
            return View::fetch();
        }
    }
}
