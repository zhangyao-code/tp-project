<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\api\middleware\IDCard;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class Bill extends BaseController
{
    //normal待支付 padding 进行中 finished 已完成 cancel 已取消

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

        $data['treatmentTime'] = strtotime($data['treatmentTime']) == 0 ? $data['treatmentTime'] : strtotime($data['treatmentTime']);

        $service = Db::name('hospital_service')->where('id', '=', $data['serviceId'])->find();
        $data['price'] = $service['price'];
        $data['sn'] = substr(md5(uniqid(rand(),1)),   8,   16);
        $data['createdTime'] = time();
        $data['updatedTime'] = time();
        $patient = Db::name('patient')->where('id', '=', $data['patientId'])->find();

        $patient['age'] = IDCard::getAgeFromIdNo($patient['IDCard']);
        $data['patientData'] = json_encode($patient);
        $data['paymentAmount'] = 0.01;
        $data['status'] = 'normal';
        $data['validityTime'] = time()+3600;

        $add_id = Db::name('hospital_bill')->insertGetId($data);
        $data['id'] = $add_id;
        $data['patientData'] = $patient;
        return json(['code'=>200,'data'=>$data]);
    }

    public function update()
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $validate = Validate::rule([
            'id|要跟新信息的id'=> 'require',
            'status|订单状态' => 'require',
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

    public function list()
    {
        $this->refresh();

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
        $count = Db::name('hospital_bill')->where($where)->count();
        foreach ($userList as $k => $vo) {
            $userList[$k]['treatmentTime'] = date('Y-m-d H:i:s', $vo['treatmentTime']);
            $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
            $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            $userList[$k]['hospital'] =  Db::name('hospital')->where('id', '=', $vo['hospitalId'])->find();
            $userList[$k]['service'] =  Db::name('hospital_service')->where('id', '=', $vo['serviceId'])->find();
            $userList[$k]['patient'] = json_decode($vo['patientData']);
            if($vo['validityTime'] - time() >0){
                $userList[$k]['validityTime'] = $vo['validityTime'] - time();
            }else{
                $userList[$k]['validityTime'] = 0;
            }
            unset($userList[$k]['patientData']);
        }
        $ajaxarr = ['code' => 200, 'total'=>$count, 'data' => $userList];
        return json($ajaxarr);
    }

    public function get()
    {
        $this->refresh();

        $data = request()->param();
        $where[] = ['id', '=',$data['id']];
        $row = Db::name('hospital_bill')->where($where)->find();

        $row['treatmentTime'] = date('Y-m-d H:i:s', $row['treatmentTime']);
        $row['createdTime'] = date('Y-m-d H:i:s', $row['createdTime']);
        $row['updatedTime'] = date('Y-m-d H:i:s', $row['updatedTime']);
        $row['hospital'] =  Db::name('hospital')->where('id', '=', $row['hospitalId'])->find();
        $row['service'] =  Db::name('hospital_service')->where('id', '=', $row['serviceId'])->find();
        $row['patient'] = json_decode($row['patientData']);
        if($row['validityTime'] - time() >0){
            $row['validityTime'] = $row['validityTime'] - time();
        }else{
            $row['validityTime'] = 0;
        }
        unset($row['patientData']);
        $ajaxarr = ['code' => 200, 'data' => $row];
        return json($ajaxarr);
    }

    private function refresh(){
        $update=[
            ['validityTime', '>', 0],
            ['validityTime', '<', time()],
            ['status', '=', 'normal']
        ];
        Db::name('hospital_bill')->where($update)->update(['status'=>'cancel']);
        $update=[
            ['treatmentTime', '<', time()],
            ['status', '=', 'padding']
        ];
        Db::name('hospital_bill')->where($update)->update(['status'=>'finished']);
    }

}
