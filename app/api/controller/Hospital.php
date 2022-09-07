<?php

namespace app\api\controller;

use app\BaseController;
use think\facade\Db;

class Hospital extends BaseController
{
//    protected $middleware = [Check::class];

    public function list(): \think\response\Json
    {
        $data = request()->param();
        $page=isset($data['page'])?$data['page']:'1';
        $limit=isset($data['limit'])?$data['limit']:'10';
        $search=isset($data['search'])?trim($data['search']):'';
        $where=[];
        if($search){
            $where[]=['name','like','%'.$search.'%'];
        }
        $where[]=['deleted','=',0];
        $userList = Db::name('hospital')->where($where)->page($page,$limit)->select()->toArray();
        foreach ($userList as $k => $vo) {
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            $departments = empty($vo['departments']) ? []: Db::name('hospital_department')->whereIn('id', explode(',',$vo['departments']))->select()->toArray();
            $userList[$k]['departments'] = $departments;
            $userList[$k]['tags'] =empty($vo['tags']) ? [] : explode(',', $vo['tags']);
        }
        $ajaxarr=['code'=>200,'data'=>$userList];
        return json($ajaxarr);
    }

    public function service(): \think\response\Json
    {
        $data = request()->param();
        $page=isset($data['page'])?$data['page']:'1';
        $limit=isset($data['limit'])?$data['limit']:'10';
        $search=isset($data['search'])?trim($data['search']):'';
        $where=[];
        if($search){
            $where[]=['name','like','%'.$search.'%'];
        }
        $userList = Db::name('hospital_service')->where($where)->page($page,$limit)->select()->toArray();
        foreach ($userList as $k => $vo) {
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
        }
        $ajaxarr=['code'=>200,'data'=>$userList];

        return json($ajaxarr);
    }

}
