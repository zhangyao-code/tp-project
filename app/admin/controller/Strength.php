<?php

/**
 * 实力保证
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

class Strength extends BaseController
{
    protected $middleware = [Check::class];

    /**
     * 实力保证列表
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
            $count=Db::name('strength')->where($where)->count();
            $strengthList=Db::name('strength')->where($where)->page($page, $limit)->select()->toArray();
            foreach ($strengthList as $k=>$vo) {
                $strengthList[$k]['add_time']=date('Y-m-d', $vo['add_time']);
            }
            return json(['data'=>$strengthList,'code'=>0,'count'=>$count]);
        } else {
            return View::fetch();
        }
    }

    /**
     * 添加实力保证
     */
    public function add()
    {
        if (request()->isAjax()) {
            $data=request()->param();
            $validate = Validate::rule([
                'title|标题' => 'require',
                'img_src|图片' => 'require',
                'desc|简介' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['add_time']=time();
                $data['add_id']=Session::get('login_user_id');
                unset($data['file']);
                $add_id=Db::name('strength')->insertGetId($data);
                if ($add_id) {
                    $ajaxarr=['code'=>200,'msg'=>'实力保证添加成功'];
                } else {
                    $ajaxarr=['code'=>400,'msg'=>'实力保证添加失败'];
                }
            }
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

    /**
     * 实力保证编辑
     */
    public function edit()
    {
        $data=request()->param();
        if (request()->isAjax()) {
            $validate = Validate::rule([
                'title|标题' => 'require',
                'img_src|图片' => 'require',
                'desc|简介' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                unset($data['file']);
                $save_id=Db::name('strength')->save($data);
                if ($save_id) {
                    $ajaxarr=['code'=>200,'msg'=>'实力保证编辑成功'];
                } else {
                    $ajaxarr=['code'=>400,'msg'=>'实力保证编辑失败'];
                }
            }
            return json($ajaxarr);
        } else {
            $id = isset($data['id']) ? $data['id'] : '';
            $strength_data = Db::name('strength')->where(['id' => $id])->field('id,title,img_src,desc,content,sort')->find();
            View::assign('strength_data', $strength_data);
            return View::fetch();
        }
    }

    /**
     * 实力保证删除
     */
    public function delete()
    {
        $data=request()->param();
        $validate = Validate::rule([
            'id|实力保证ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('strength')->where(['id'=>$data['id']])->save(['status'=>0,'del_time'=>time(),'del_id'=>Session::get('login_user_id')]);
            if ($save_id) {
                $ajaxarr=['code'=>200,'msg'=>'实力保证删除成功'];
            } else {
                $ajaxarr=['code'=>400,'msg'=>'实力保证删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
