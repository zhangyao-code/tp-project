<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class Patient extends BaseController
{
    protected $middleware = [Check::class];

    public function add(): \think\response\Json
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $data = request()->param();
        $validate = Validate::rule([
            'name|就诊人姓名' => 'require',
            'gender|性别' => 'require',
            'mobile|手机号'=> 'require|mobile',
            'IDCard|身份证号'=> 'require',
            'relation|就诊人关系'=> 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $user = $this->getCurrentUser();
        $data['createdTime']=time();
        $data['status']='normal';
        $data['gender']=$data['gender'] == '男' ? "男" : "女";
        $data['createdUser']=$user['id'];
        try {
            $add_id=Db::name('patient')->insertGetId($data);
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }

        $data['id'] = $add_id;
        return json(['code'=>200,'data'=>$data]);
    }

    public function get(): \think\response\Json
    {
        $data = request()->param();
        $validate = Validate::rule([
            'id|就诊人id' => 'require',
        ]);
        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        try {
            $list=Db::name('patient')->where('id', '=', $data['id'])->find();
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }

        return json(['code'=>200,'data'=>$list]);
    }

    public function list(): \think\response\Json
    {
        $user = $this->getCurrentUser();
        $data = request()->param();
        $search=isset($data['name']) ? trim($data['name']) : '';
        $where=[];
        if ($search) {
            $where[]=['name','like','%'.$search.'%'];
        }
        $where[]=['createdUser', "=", $user['id']];
        $where[]=['status', '=', 'normal'];
        try {
            $list=Db::name('patient')->where($where)->select()->toArray();
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }

        return json(['code'=>200,'data'=>$list]);
    }

    public function update(): \think\response\Json
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $data = request()->param();
        $validate = Validate::rule([
            'id|要跟新信息的id'=> 'require',
            'name|就诊人姓名' => 'require',
            'gender|性别' => 'require',
            'mobile|手机号'=> 'require|mobile',
            'IDCard|身份证号'=> 'require',
            'relation|就诊人关系'=> 'require',
        ]);

        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $patient = Db::name('patient')->where('id', '=', $data['id'])->find();
        if (empty($patient)) {
            return json(['code'=>400,'msg'=>'就诊信息不存在，请确认id是否正确']);
        }
        $data['status']='normal';
        $data['gender']=$data['gender'] == '男' ? "男" : "女";
        $data['updatedTime'] = time();

        try {
            $add_id=Db::name('patient')->save($data);
            if (empty($add_id)) {
                return json(['code'=>400,'msg'=>'跟新失败']);
            }
        } catch (\Exception $exception) {
            return json(['code' => 100, 'msg' => $exception->getMessage()]);
        }

        $data['id'] = $add_id;
        return json(['code'=>200,'data'=>$data]);
    }

    public function delete()
    {
        if (request()->isGet()) {
            return json("请使用 post 提交");
        }
        $data = request()->param();
        $validate = Validate::rule([
            'id|要删除的id'=> 'require',
        ]);

        if (!$validate->check($data)) {
            return json(['code' => 100, 'msg' => $validate->getError()]);
        }
        $patient = Db::name('patient')->where('id', '=', $data['id'])->find();
        if (empty($patient)) {
            return json(['code'=>400,'msg'=>'就诊信息不存在，请确认id是否正确']);
        }
        $id=Db::name('patient')->save([
            'id'=>$data['id'],
            'status'=>'deleted'
        ]);
        if (empty($id)) {
            return json(['code'=>400,'msg'=>'删除失败']);
        }
        return  json(['code'=>200,'msg'=>'删除成功']);
    }
}
