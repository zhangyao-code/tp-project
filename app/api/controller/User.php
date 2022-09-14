<?php

namespace app\api\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class User extends BaseController
{
    public function index()
    {
        $data = request()->param();
        $validate = Validate::rule([
            'id' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $user_data=Db::name('user')->where(['id'=>$data['id']])->where('status', '<>', 0)->find();

        return $this->_sayOk(['code'=>200,'data'=>$user_data]);
    }

    public function initUser()
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $data = request()->param();
        $validate = Validate::rule([
            'sign|签名'=> 'require',
            'openid|openid'=> 'require',
            'nickName|用户昵称' => 'require',
            'avatarUrl|头像' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        if (md5($data['openid']) != $data['sign']) {
            return json(['code' => 100, 'msg' => '签名错误，无法创建用户']);
        }
        $user_data=Db::name('user')->where(['username'=>$data['openid']])->where('status', '<>', 0)->find();
        if(!empty($user_data)){
            $token = md5($data['openid'].time());
            Db::name('user_session')->insert([
                'userId' =>$user_data['id'],
                'token' => $token,
                'alive_before' => time()+14400,
                'create_time' => date('Y-m-d H:i:s', time()),
                'update_time' => date('Y-m-d H:i:s', time()),
            ]);
            return $this->_sayOk(['code'=>200, 'data' =>['token'=>$token]]);
        }

        $user = [
            'username'=>$data['openid'],
            'truename'=>$data['nickName'],
            'avatar' => $data['avatar'],
            'status' =>1,
        ];
        $add_id=Db::name('user')->insertGetId($user);
        $token = md5($data['openid'].time());
        Db::name('user_session')->insert([
            'userId' =>$add_id,
            'token' => $token,
            'alive_before' => time()+14400,
            'create_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time()),
        ]);
        if ($add_id) {
            $ajaxarr=['code'=>200, 'data' =>['token'=>$token]];
        } else {
            $ajaxarr=['code'=>400, 'data' =>['token'=>$token]];
        }
        return $this->_sayOk($ajaxarr);
    }
}
