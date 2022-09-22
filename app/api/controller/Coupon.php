<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;

class Coupon extends BaseController
{
    public function index(): \think\response\Json
    {
        $result = $this->handle();
        if(empty(!empty($result))){
            return json($result);
        }
        $data = request()->param();
        $where=[];
        $where[] = ['userId','=', $this->getCurrentUser()['id']];
        $search=isset($data['search']) ? trim($data['search']) : '';
        if(!empty($search)){
            switch ($search){
                case 'normal':
                    $where[] = ['status','=', 'normal'];
                    $where[] = ['deadline','>', date('Y-m-d H:i:s')];
                break;
                case 'used':
                    $where[] = ['status','=', 'used'];
                    break;
                case 'expired':
                    $where[] = ['status','=', 'normal'];
                    $where[] = ['deadline','<=', date('Y-m-d H:i:s')];
                    break;
                default:
                    $where[] = ['status','<>', 'delete'];
                    break;
            }
        }

        $userList = Db::name('coupon')->where($where)->select()->toArray();
        foreach ($userList as $k => $vo) {
            unset($userList[$k]['parentId']);
            unset($userList[$k]['userId']);
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
        }
        $ajaxarr=['code'=>200,'data'=>$userList];
        return json($ajaxarr);
    }

    public function list(): \think\response\Json
    {
        $where[] = ['userId','=', null];
        $where[] = ['deadline','>', date('Y-m-d H:i:s')];
        $userList = Db::name('coupon')->where($where)->select()->toArray();
        foreach ($userList as $k => $vo) {
            unset($userList[$k]['parentId']);
            unset($userList[$k]['userId']);
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
        }
        $ajaxarr=['code'=>200,'data'=>$userList];
        return json($ajaxarr);
    }

    public function receive(): \think\response\Json
    {
        $result = $this->handle();
        if(empty(!empty($result))){
            return json($result);
        }
        $data = request()->param();
        Db::startTrans();
        try{
            $where[] = ['userId','=', null];
            $where[] = ['id','=', $data['id']];
            $where[] = ['status','=', 'normal'];
            $where[] = ['deadline','>', date('Y-m-d H:i:s')];
            $coupon = Db::name('coupon')->where($where)->find();

            if(!empty($coupon)){
                $coupon['updatedTime'] = time();
                $coupon['userId'] = $this->getCurrentUser()['id'];
                Db::name('coupon')->save($coupon);
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>500,'data'=>'领取失败！']);
        }

        $ajaxarr=['code'=>200,'data'=>empty($coupon) ? '优惠券已被别人抢先领取！': '领取成功！'];
        return json($ajaxarr);
    }

    public function handle($request, \Closure $next)
    {
        $token = $request->header('token');

        if (empty($token)) {
            return ['code'=> 403, 'message'=> 'Authentication error, header->token is empty'];
        }
        $user =Db::name('user_session')->where(['token'=>$token])->find();
        if (empty($user)) {
            return ['code'=> 403, 'message'=> 'Authentication error, header->token is error！'];
        }
        if ($user['alive_before'] < time()) {
            return ['code'=> 403, 'message'=> 'Authentication error, header->token expired！'];
        }
        return [];
    }

}
