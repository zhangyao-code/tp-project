<?php

namespace app\api\controller;

use app\BaseController;
use GuzzleHttp\Exception\RequestException;
use think\facade\Db;
use think\facade\Validate;

class WeChat extends BaseController
{
    public function index()
    {
        $data = request()->param();
        $validate = Validate::rule([
            'code|登陆code' => 'require',
            'nickName|昵称' => 'require',
            'avatarUrl|头像' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $data = (new \app\api\WeChat\WeChat())->getJscode2session($data['code']);
        if(empty($data['openid'])){
            return $this->_sayOk(['code'=>500,'data'=>json_encode($data)]);
        }
        $user_data=Db::name('user')->where('username', '=', $data['openid'])->find();
        if(empty($user_data)){
            $user = [
                'username'=>$data['openid'],
                'truename'=>$data['nickName'],
                'avatar' => $data['avatarUrl'],
                'status' =>1,
            ];
            $add_id=Db::name('user')->insertGetId($user);
            $user_data=Db::name('user')->where('id', '=', $add_id)->find();
        }
        $token = md5($data['openid'].time());
        if(!empty($data['userId'])){
            $user =Db::name('retail')->where('parentUserId', '=', $data['userId'])->where('userId', '=',$user_data['id'])->find();
            if(empty($user)){
                Db::name('retail')->insertGetId([
                    'userId'=>$user_data['id'],
                    'parentUserId' => $data['userId'],
                    'createdTime' => time()
                ]);
            }
        }
        Db::name('user_session')->insert([
            'userId' =>$user_data['id'],
            'token' => $token,
            'alive_before' => time()+14400,
            'create_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time()),
        ]);
        $user_data['token'] = $token;
        return $this->_sayOk(['code'=>200,'data'=>$user_data]);
    }
}
