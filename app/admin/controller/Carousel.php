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

class Carousel extends BaseController
{
    protected $middleware = [Check::class];
    /**
     * 菜单列表
     */
    public function index()
    {
        $tableData = Db::name('setting')->where(['code' => 'carouselSetting'])->find();

        if (request()->isAjax()) {
            $data = request()->param();
            if (empty($tableData)) {
                $add_id = Db::name('setting')->insertGetId([
                    'code'=>'carouselSetting',
                    'content' => json_encode($data),
                    'createdTime' => time(),
                    'hide'=>1
                ]);
            } else {
                $tableData['content'] = json_encode($data);
                Db::name('setting')->save($tableData);
            }
            $ajaxarr = ['code' => 200, 'msg' => '设置成功'];

            return json($ajaxarr);
        } else {
            $setting = json_decode($tableData['content'],true);

            View::assign('setting', $setting);
            return View::fetch();
        }
    }
}
