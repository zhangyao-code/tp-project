<?php

/**
 * 免费学
 * 开发者：浮生若梦
 * 开发时间：2020/9/8
 */

namespace app\admin\controller;

use app\admin\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;

class FreeStudy extends BaseController
{
    protected $middleware = [Check::class];
    /**
     * 免费直播课列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            $data = request()->param();
            $page=isset($data['page']) ? $data['page'] : '1';
            $limit=isset($data['limit']) ? $data['limit'] : '10';
            $search=isset($data['search']) ? $data['search'] : '';
            $status=isset($data['status']) ? $data['status'] : '';
            if ($status) {
                $where[]=['status','=',$status];
            }
            if ($search) {
                $where[]=['name|telphone|remark','like','%'.$search.'%'];
            }
            $where[]=['status','<>',0];
            $count=Db::name('free_study')->where($where)->count();
            $liveList=Db::name('free_study')->where($where)->page($page, $limit)->select()->toArray();
            foreach ($liveList as $k=>$vo) {
                $liveList[$k]['add_time']=date('Y-m-d', $vo['add_time']);
            }
            return json(['data'=>$liveList,'code'=>0,'count'=>$count]);
        } else {
            return View::fetch();
        }
    }

    /**
     * 处理
     */
    public function change_status()
    {
        $data=request()->param();
        $validate = Validate::rule([
            'id|信息ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('free_study')->where(['id'=>$data['id']])->save(['status'=>2,'edit_time'=>time(),'edit_id'=>Session::get('login_user_id')]);
            if ($save_id) {
                $ajaxarr=['code'=>200,'msg'=>'处理成功'];
            } else {
                $ajaxarr=['code'=>400,'msg'=>'处理失败'];
            }
        }
        return json($ajaxarr);
    }
}
