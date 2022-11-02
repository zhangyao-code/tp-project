<?php

namespace app\admin\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;

class Login extends BaseController
{
    /**
     * 显示登陆页面
     */
    public function index()
    {
        if (Session::has('login_user_id')) {
            return redirect('/admin/index');
        } else {
            return View::fetch();
        }
    }

    /**
     * 登陆
     */
    public function login()
    {
        $data=request()->param();
        $validate = Validate::rule([
            'username|用户名' => 'require',
            'password|密码'=>'require'
        ]);

        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $user_data=Db::name('user')->where(['username'=>$data['username']])->where('status', '<>', 0)->find();
            if ($user_data) {
                if ($user_data['status'] == 1) {
                    if ($user_data['password'] == md5($data['password'].$user_data['salt'])) {
                        Session::set('login_user_id', $user_data['id']);
                        $ajaxarr = ['code' => 200, 'msg' => '登陆成功'];
                    } else {
                        $ajaxarr = ['code' => 400, 'msg' => '密码错误'];
                    }
                } else {
                    $ajaxarr = ['code' => 400, 'msg' => '账户已被禁用'];
                }
            } else {
                $ajaxarr = ['code' => 400, 'msg' => '未查询到此用户'];
            }
        }
        return json($ajaxarr);
    }

    /**
     * 登出
     */
    public function logout()
    {
        Session::clear();
        return redirect('/admin/Login');
    }
}
