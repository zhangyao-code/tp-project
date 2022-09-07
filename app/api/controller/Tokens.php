<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;

class Tokens extends BaseController
{
    public function index()
    {
        $user = $this->getCurrentUser();
        if(!empty($user)){
            $token = md5($user['username'].time());
            Db::name('user_session')->insert([
                'userId' =>$user['id'],
                'token' => $token,
                'alive_before' => time()+14400,
                'create_time' => date('Y-m-d H:i:s', time()),
                'update_time' => date('Y-m-d H:i:s', time()),
            ]);
            return $this->_sayOk(['code' => 200, 'data' =>['token'=>$token]]);
        }
        $data = request()->param();
        $validate = Validate::rule([
            'username|用户名' => 'require',
            'password|密码' => 'require',
        ]);

        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => "请使用`用户名/密码`获取token"]);
        }
        $username = request()->post('username', '');
        $password = request()->post('password', '');

        $user_data=Db::name('user')->where(['username'=>$username])->where('status','<>',0)->find();
        if($user_data){
            if($user_data['status'] == 1) {
                if ($user_data['password'] == md5($password.$user_data['salt'])) {
                    $token = md5($username.time());
                    Db::name('user_session')->insert([
                        'userId' =>$user_data['id'],
                        'token' => $token,
                        'alive_before' => time()+14400,
                        'create_time' => date('Y-m-d H:i:s', time()),
                        'update_time' => date('Y-m-d H:i:s', time()),
                    ]);
                    $ajaxarr = ['code' => 200, 'data' =>['token'=>$token]];
                } else {
                    $ajaxarr = ['code' => 400, 'msg' => '密码错误'];
                }
            }else{
                $ajaxarr = ['code' => 400, 'msg' => '账户已被禁用'];
            }
        }else{
            $ajaxarr = ['code' => 400, 'msg' => '未查询到此用户'];
        }

        return $this->_sayOk($ajaxarr);
    }



}
