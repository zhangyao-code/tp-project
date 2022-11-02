<?php

/**
 * 免费直播课
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

class FreeLive extends BaseController
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
            $where[]=['status','<>',0];
            $search=isset($data['search']) ? $data['search'] : '';
            if ($search) {
                $where[]=['title','like','%'.$search.'%'];
            }
            $count=Db::name('free_live')->where($where)->count();
            $liveList=Db::name('free_live')->where($where)->page($page, $limit)->select()->toArray();
            foreach ($liveList as $k=>$vo) {
                $liveList[$k]['add_time']=date('Y-m-d', $vo['add_time']);
            }
            return json(['data'=>$liveList,'code'=>0,'count'=>$count]);
        } else {
            return View::fetch();
        }
    }

    /**
     * 添加直播
     */
    public function add()
    {
        if (request()->isAjax()) {
            $data=request()->param();
            $validate = Validate::rule([
                'title|直播标题' => 'require',
                'img_src|直播图片' => 'require',
                'live_src|直播链接' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['add_time']=time();
                $data['add_id']=Session::get('login_user_id');
                unset($data['file']);
                $add_id=Db::name('free_live')->insertGetId($data);
                if ($add_id) {
                    $ajaxarr=['code'=>200,'msg'=>'直播添加成功'];
                } else {
                    $ajaxarr=['code'=>400,'msg'=>'直播添加失败'];
                }
            }
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

    /**
     * 编辑直播
     */
    public function edit()
    {
        $data=request()->param();
        if (request()->isAjax()) {
            $validate = Validate::rule([
                'title|直播标题' => 'require',
                'img_src|直播图片' => 'require',
                'live_src|直播链接' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                unset($data['file']);
                $save_id=Db::name('free_live')->save($data);
                if ($save_id) {
                    $ajaxarr=['code'=>200,'msg'=>'直播编辑成功'];
                } else {
                    $ajaxarr=['code'=>400,'msg'=>'直播编辑失败'];
                }
            }
            return json($ajaxarr);
        } else {
            $id = isset($data['id']) ? $data['id'] : '';
            $live_data = Db::name('free_live')->where(['id' => $id])->field('id,title,img_src,speaker,desc,live_src')->find();
            View::assign('live_data', $live_data);
            return View::fetch();
        }
    }

    /**
     * 直播删除
     */
    public function delete()
    {
        $data=request()->param();
        $validate = Validate::rule([
            'id|直播ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('free_live')->where(['id'=>$data['id']])->save(['status'=>0,'del_time'=>time(),'del_id'=>Session::get('login_user_id')]);
            if ($save_id) {
                $ajaxarr=['code'=>200,'msg'=>'直播删除成功'];
            } else {
                $ajaxarr=['code'=>400,'msg'=>'直播删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
