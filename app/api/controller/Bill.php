<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class Bill extends BaseController
{
    protected $middleware = [Check::class];

    public function save(): \think\response\Json
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $validate = Validate::rule([
            'hospitalId|医院' => 'require',
            'serviceId|下单服务' => 'require',
            'treatmentTime|期望就诊时间' => 'require',
            'patientId|期望就诊时间' => 'require',
        ]);
        $data = request()->param();
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $data['userId'] = $this->getCurrentUser()['id'];
        $data['treatmentTime'] = strtotime($data['treatmentTime']);
        $data['status'] = 'normal';
        $data['sn'] = str_ireplace('.', '', uniqid(mt_rand(), true));
        $data['createdTime'] = time();
        $data['updatedTime'] = time();
        $add_id = Db::name('hospital_bill')->insertGetId($data);
        $data['id'] = $add_id;
        return json(['code'=>200,'data'=>$data]);
    }

    public function list()
    {
        $data = request()->param();
        $page = isset($data['page']) ? $data['page'] : '1';
        $limit = isset($data['limit']) ? $data['limit'] : '10';
        $search = isset($data['search']) ? trim($data['search']) : '';
        $where = [];
        if ($search) {
            $where[] = ['status', '=',$search];
        }
        $where[] = ['userId', '=',$this->getCurrentUser()['id']];
        $userList = Db::name('hospital_bill')->where($where)->page($page, $limit)->select()->toArray();

        foreach ($userList as $k => $vo) {
            $userList[$k]['treatmentTime'] = date('Y-m-d H:i:s', $vo['treatmentTime']);
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            $userList[$k]['hospital'] =  Db::name('hospital')->where('id', '=', $vo['hospitalId'])->find();
            $userList[$k]['service'] =  Db::name('hospital_service')->where('id', '=', $vo['serviceId'])->find();
            $userList[$k]['patient'] =  Db::name('patient')->where('id', '=',$vo['patientId'])->find();
        }
        $ajaxarr = ['code' => 0, 'data' => $userList];
        return json($ajaxarr);
    }

}
