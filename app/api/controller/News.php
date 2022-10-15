<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\api\middleware\IDCard;
use app\BaseController;
use think\facade\Db;
use think\facade\Validate;

class News extends BaseController
{
    protected $middleware = [Check::class];

    public function list()
    {
        $data = request()->param();
        $page = isset($data['page']) ? $data['page'] : '1';
        $limit = isset($data['limit']) ? $data['limit'] : '10';

        $where[] = ['del_id', '=',null];
        $userList = Db::name('news')->where($where)->page($page, $limit)->order('show_num','DESC')->select()->toArray();
        $count = Db::name('news')->where($where)->count();
        foreach ($userList as $k => $vo) {
            $userList[$k]['img_src'] =$vo['img_src']?$this->host.$vo['img_src']:$vo['img_src'];
            $userList[$k]['publish_time'] =date('Y-m-d', $vo['publish_time']);
            unset($userList[$k]['add_time']);
            unset($userList[$k]['add_id']);
        }
        $ajaxarr = ['code' => 200, 'total'=>$count, 'data' => $userList];
        return json($ajaxarr);
    }

    public function get()
    {
        $data = request()->param();
        $where[] = ['id', '=',$data['id']];

        $row = Db::name('news')->where($where)->find();

        if(!empty($row)){
            $row['show_num'] = $row['show_num']+1;
            Db::name('news')->save($row);
            $row['img_src'] =$row['img_src']?$this->host.$row['img_src']:$row['img_src'];
            $row['publish_time'] =date('Y-m-d', $row['publish_time']);

        }

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
