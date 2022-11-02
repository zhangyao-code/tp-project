<?php

namespace app\admin\controller;

use app\admin\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;
use think\facade\View;

class Bill extends BaseController
{
    //normal待支付 paiding 进行中 finished 已完成 cancel 已取消

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

    public function update()
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $validate = Validate::rule([
            'id|要跟新信息的id'=> 'require',
            'status|就诊人姓名' => 'require',
        ]);
        $data = request()->param();
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $bill = Db::name('hospital_bill')->where('id', '=', $data['id'])->find();

        $bill['status'] = $data['status'];

        try {
            $add_id = Db::name('hospital_bill')->save($bill);
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }
        return json(['code' => 200, 'msg' =>'跟新成功']);
    }


    public function review()
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $validate = Validate::rule([
            'id|要更新信息的id'=> 'require',
            'type|是否通过' => 'require',
        ]);
        $data = request()->param();

        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $bill = Db::name('hospital_bill')->where('id', '=', $data['id'])->find();

        $bill['review'] = $data['type'];
        if($data['type'] == 'pass'){
            $bill['status'] = 'cancel';
        } else {
            $bill['status'] = 'finished';
        }

        try {
            $add_id = Db::name('hospital_bill')->save($bill);
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }
        return json(['code' => 200, 'msg' =>'更新成功']);
    }
    
        public function detail()
    {
        $data = request()->param();
        $bill = Db::name('hospital_bill')->where('id', '=', $data['id'])->find();
        $hospital_service = Db::name('hospital_service')->where('id', '=', $bill['serviceId'])->find();
        $hospital = Db::name('hospital')->where('id', '=', $bill['hospitalId'])->find();
        $status = [
            'review' => '审核中',
            'normal' => '待支付',
            'padding' => '进行中',
            'finished' => '已完成',
            'cancel' => '已取消',
            'reviewPass' => '取消订单成功',
        ];
        $bill['status'] = $status[$bill['status']];
        $bill["patientData"] = json_decode($bill["patientData"], true);
        $bill["otherData"] = json_decode($bill["otherData"], true);
        if ($bill["otherData"] && $bill["otherData"]["addressId"]) {
            $address = Db::name('address')->where('id', '=', $bill["otherData"]["addressId"])->find();
            if ($address) {
                $address["setting"] = json_decode($address["setting"], true);
                $bill["address"] = $address["setting"];
            }
        }
        View::assign('hospital_service', $hospital_service);
        View::assign('hospital', $hospital);
        View::assign('bill', $bill);
        return View::fetch();
    }
    
    public function index()
    {
        if (request()->isAjax()) {
            $data = request()->param();
            $page = isset($data['page']) ? $data['page'] : '1';
            $limit = isset($data['limit']) ? $data['limit'] : '10';
            $search = isset($data['search']) ? trim($data['search']) : '';
            $user = isset($data['user']) ? trim($data['user']) : '';
            $status = isset($data["status"]) && $data["status"] !== 'all' ? trim($data["status"]) : '';
            $where = [];
            if ($search) {
                $userList = Db::name('hospital')->where('name', 'like', "%$search%")->page(0, 10000)->select()->toArray();
                $where[] = ['hospitalId', 'in',array_column($userList, 'id')];
            }
            if ($user) {
                $userList = Db::name('user')->where('truename', 'like', "%$user%")->page(0, 10000)->select()->toArray();
                $where[] = ['contact', 'like', "%{$user}%"];
            }
            if ($status) {
                 $where[] = ['status', '=', $status];
            }
            $userList = Db::name('hospital_bill')->where($where)->page($page, $limit)->order("id", "desc")->select()->toArray();
               $count = Db::name('hospital_bill')->where($where)->count('id');
             $status = [
               'review' => '审核中',
               'normal' => '待支付',
               'padding' => '进行中',
               'finished' => '已完成',
               'cancel' => '已取消'
           ];
            foreach ($userList as $k => $vo) {
                $userList[$k]['treatmentTime'] = date('Y-m-d H:i:s', $vo['treatmentTime']);
                $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
                $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
                $hospital=  Db::name('hospital')->where('id', '=', $vo['hospitalId'])->find();
                $userList[$k]['hospital'] = $hospital['name'];
                $service =  Db::name('hospital_service')->where('id', '=', $vo['serviceId'])->find();
                $userList[$k]['service'] = $service['name'];
                $userList[$k]['patient'] =  Db::name('patient')->where('id', '=', $vo['patientId'])->find();
                $user = Db::name('user')->where('id', '=', $vo['userId'])->find();
                $userList[$k]['user'] = $user['truename'];
                $userList[$k]['status'] = $status[$vo['status']];
            }
            $return = [
                "status" => $status,
                "list" => $userList
            ];
            $ajaxarr = ['code' => 0, 'data' => $userList, 'count'=>$count ];
            return json($ajaxarr);
        } else {
            return View::fetch();
        }

    }
}
