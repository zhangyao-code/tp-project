<?php

namespace app\api\controller;

use app\BaseController;
use think\facade\Db;

class Hospital extends BaseController
{
    public function get(): \think\response\Json
    {
        $data = request()->param();
        $row = Db::name('hospital')->where('id', '=', $data['id'])->find();
        $row['createdTime'] = date('Y-m-d H:i:s', $row['createdTime']);
        $row['updatedTime'] = date('Y-m-d H:i:s', $row['updatedTime']);
        $row['img'] =$row['img']?$this->host.$row['img']:$row['img'];
        $departments = empty($row['departments']) ? [] : Db::name('hospital_department')->whereIn('id', explode(',', $row['departments']))->select()->toArray();
        $row['departments'] = $departments;
        $row['tags'] =empty($row['tags']) ? [] : explode(',', $row['tags']);
        $ajaxarr=['code'=>200, 'data'=>$row];
        return json($ajaxarr);
    }

    public function list(): \think\response\Json
    {
        $data = request()->param();
        $page=isset($data['page']) ? $data['page'] : '1';
        $limit=isset($data['limit']) ? $data['limit'] : '10';
        $search=isset($data['search']) ? trim($data['search']) : '';
        $where=[];
        if ($search) {
            $where[]=['name','like','%'.$search.'%'];
        }
        $where[]=['deleted','=',0];
        $total = Db::name('hospital')->where($where)->count('id');
        $userList = Db::name('hospital')->where($where)->page(1, 100000)->select()->toArray();
        foreach ($userList as $k => $vo) {
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            $userList[$k]['img'] =$vo['img']?$this->host.$vo['img']:$vo['img'];
            $departments = empty($vo['departments']) ? [] : Db::name('hospital_department')->whereIn('id', explode(',', $vo['departments']))->select()->toArray();
            $userList[$k]['departmentsArr'] = $departments;
            $userList[$k]['departments'] =implode('、', array_column($departments, 'name'));
            $userList[$k]['tags'] =empty($vo['tags']) ? [] : explode(',', $vo['tags']);
        }
        $ajaxarr=['code'=>200,'total'=>$total, 'data'=>$userList];
        return json($ajaxarr);
    }

    public function service(): \think\response\Json
    {
        $data = request()->param();
        $page=isset($data['page']) ? $data['page'] : '1';
        $limit=isset($data['limit']) ? $data['limit'] : '10';
        $search=isset($data['search']) ? trim($data['search']) : '';
        $where=[];
        if ($search) {
            $where[]=['name','like','%'.$search.'%'];
        }
        $total = Db::name('hospital_service')->where($where)->count('id');
        $userList = Db::name('hospital_service')->where($where)->page($page, $limit)->select()->toArray();
        foreach ($userList as $k => $vo) {
            $userList[$k]['detailImg'] = $this->host.$vo['detailImg'];
            $userList[$k]['img'] = $vo['img']?$this->host.$vo['img']:$vo['img'];
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
        }
        $ajaxarr=['code'=>200,'total'=>$total, 'data'=>$userList];

        return json($ajaxarr);
    }

    public function serviceGet(): \think\response\Json
    {
        $data = request()->param();
        $row = Db::name('hospital_service')->where('id', '=', $data['id'])->find();
        $row['img'] = $row['img']?$this->host.$row['img']:$row['img'];
        $row['detailImg'] = $this->host.$row['detailImg'];
        $row['createdTime'] = date('Y-m-d H:i:s', $row['createdTime']);
        $row['updatedTime'] = date('Y-m-d H:i:s', $row['updatedTime']);
        $ajaxarr=['code'=>200,'data'=>$row];

        return json($ajaxarr);
    }
}
