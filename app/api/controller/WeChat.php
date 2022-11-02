<?php

namespace app\api\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Validate;
use app\api\SMS\SMS;


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
        $retailUserId = empty($data['userId']) ? 0 : $data['userId'];

        $wedata = (new \app\api\WeChat\WeChat())->getJscode2session($data['code']);
        if(empty($wedata['openid'])){
            return $this->_sayOk(['code'=>500,'data'=>json_encode($wedata)]);
        }
        $user_data=Db::name('user')->where('username', '=', $wedata['openid'])->find();
        if(empty($user_data)){
            $user = [
                'username'=>$wedata['openid'],
                'truename'=>$data['nickName'],
                'avatar' => $data['avatarUrl'],
                'status' =>1,
            ];
            $add_id=Db::name('user')->insertGetId($user);
            $user_data=Db::name('user')->where('id', '=', $add_id)->find();
        }
        if($retailUserId){
            $user =Db::name('retail')
            //->where('parentUserId', '=', $retailUserId)
            ->where('userId', '=',$user_data['id'])
            ->find();
            if(empty($user)){
                Db::name('retail')->insertGetId([
                    'userId'=>$user_data['id'],
                    'parentUserId' => $retailUserId,
                    'createdTime' => time()
                ]);
            }
        }
        $token = md5($wedata['openid'].time());
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
    
    
      public function sms(){
        $bill = Db::name('hospital_bill')->where('sn', '=', 'cdcddd63111cc322')->find();
                $user = Db::name('user')->where('id', '=', $bill['userId'])->find();
         $user = empty($bill['contact']) ? $user['truename'] : $bill['contact'];
         var_dump($user);
        // $bill = Db::name('hospital_bill')->where('sn', '=', 'cdcddd63111cc322')->find();
        // $user = empty($bill['contact']) ? $this->getCurrentUser()['username'] : $bill['contact'];
        // $service = Db::name('hospital_service')->where('id', '=', $bill['serviceId'])->find();
        // $result =SMS::main($user, $service['name'], $service['price']);
        // var_dump($result);
//        SMS::main();
    }
}
