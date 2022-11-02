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
        $limit=isset($data['limit']) ? $data['limit'] : '1000000';
        $search=isset($data['search']) ? trim($data['search']) : '';
        $city=isset($data['city']) ? trim($data['city']) : '';
        $where=[];
        if ($search) {
            $where[]=['name','like','%'.$search.'%'];
        }
        if ($city) {
            $where[]=['city','=', $city];
        }
        $where[]=['deleted','=',0];
            $total = Db::name('hospital')->where($where)->whereOr('id','=', 9)->count('id');
        $userList = Db::name('hospital')->where($where)->whereOr('id','=', 9)->page(1, 100000)->select()->toArray();
        
        foreach ($userList as $k => $vo) {
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            $userList[$k]['img'] =$vo['img']?$this->host.$vo['img']:$vo['img'];
            $departments = empty($vo['departments']) ? [] : Db::name('hospital_department')->whereIn('id', explode(',', $vo['departments']))->select()->toArray();
            $userList[$k]['departmentsArr'] = $departments;
            $userList[$k]['departments'] =implode('、', array_column($departments, 'name'));
            $userList[$k]['tags'] =empty($vo['tags']) ? [] : explode(',', $vo['tags']);
        }
        $ajaxarr=['code'=>200, 'data'=>$userList];
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

        $result = [];
        foreach ($userList as $k => $vo) {
            $userList[$k]['detailImg'] = $this->host.$vo['detailImg'];
            $userList[$k]['img'] = $vo['img']?$this->host.$vo['img']:$vo['img'];
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            $userList[$k]['belongsType'] = '';
            if(in_array($vo['id'], [1,2,3])){
                $userList[$k]['belongsType'] = '就医陪诊';
                $result['就医陪诊'][] = $userList[$k];
                
            }
            if(in_array($vo['id'], [9,10,11])){
                $userList[$k]['belongsType'] = '检查预约';
                $result['检查预约'][] = $userList[$k];
               
            }
        }

        if(!empty($data['belongsType'])){
            $userList = $result[$data['belongsType']];
        }

        $ajaxarr=['code'=>200,'total'=>$total, 'data'=>$userList];

        return json($ajaxarr);
    }
    
    public function serviceGet(): \think\response\Json
    {
        $data = request()->param();
        $row = Db::name('hospital_service')->where('id', '=', $data['id'])->find();
        $row['img'] = $row['img']?$this->host.$row['img']:$row['img'];
        $row['detailImg'] =$row['detailImg'] ? $this->host.$row['detailImg']:'';
        $row['createdTime'] = date('Y-m-d H:i:s', $row['createdTime']);
        $row['updatedTime'] = date('Y-m-d H:i:s', $row['updatedTime']);

        $ajaxarr=['code'=>200,'data'=>$row];

        return json($ajaxarr);
    }
}
