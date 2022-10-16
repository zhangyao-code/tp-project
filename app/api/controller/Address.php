<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\api\middleware\IDCard;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class Address extends BaseController
{
    protected $middleware = [Check::class];

    public function add(): \think\response\Json
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $data = request()->param();
        $validate = Validate::rule([
            'setting|地址设置' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $data['setting']= json_encode($data['setting']);
        $data['userId']=$this->getCurrentUser()['id'];
        try {
            $add_id=Db::name('address')->insertGetId($data);
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }

        $data['id'] = $add_id;
        $data['setting']= json_decode($data['setting'], true);
        return json(['code'=>200,'data'=>$data]);
    }

    public function get(): \think\response\Json
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $data = request()->param();
        $validate = Validate::rule([
            'id|地址id' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $data = Db::name('address')->where('id', '=', $data['id'])->find();


        $data['setting']= json_decode($data['setting'], true);
        return json(['code'=>200,'data'=>$data]);
    }

    public function list()
    {
        $data = request()->param();
        $page = isset($data['page']) ? $data['page'] : '1';
        $limit = isset($data['limit']) ? $data['limit'] : '10';
        $where[] = ['userId', '=',$this->getCurrentUser()['id']];
        $userList = Db::name('address')->where($where)->page($page, $limit)->select()->toArray();
        $count = Db::name('address')->where($where)->count();
        foreach ($userList as $k => $vo) {
                $userList[$k]['setting'] = json_decode($vo['setting'], true);
        }
        $ajaxarr = ['code' => 200, 'total'=>$count, 'data' => $userList];
        return json($ajaxarr);
    }

    public function update()
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $validate = Validate::rule([
            'id|要跟新信息的id'=> 'require',
            'setting|地址设置' => 'require',
        ]);
        $data = request()->param();
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $bill = Db::name('address')->where('id', '=', $data['id'])->find();

        $bill['setting'] = json_encode($data['setting']);

        try {
            $add_id = Db::name('address')->save($bill);
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }
        return json(['code' => 200, 'msg' =>'更新成功']);
    }

    public function delete()
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $validate = Validate::rule([
            'id|要删除信息的id'=> 'require',
        ]);
        $data = request()->param();
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $bill = Db::name('address')->where('id', '=', $data['id'])->find();

        try {
            Db::name('address')->delete($bill);
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }
        return json(['code' => 200, 'msg' =>'删除成功']);
    }

}
