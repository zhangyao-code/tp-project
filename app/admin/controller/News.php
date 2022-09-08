<?php

/**
 * 新闻管理
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

class News extends BaseController
{
    protected $middleware = [Check::class];
    /**
     * 联系我们列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            $data = request()->param();
            $page=isset($data['page']) ? $data['page'] : '1';
            $limit=isset($data['limit']) ? $data['limit'] : '10';
            $search=isset($data['search']) ? $data['search'] : '';
            if ($search) {
                $where[]=['title|desc','like','%'.$search.'%'];
            }
            $where[]=['status','<>',0];
            $count=Db::name('news')->where($where)->count();
            $newsList=Db::name('news')->where($where)->page($page, $limit)->select()->toArray();
            foreach ($newsList as $k=>$vo) {
                $newsList[$k]['add_time']=date('Y-m-d', $vo['add_time']);
                $newsList[$k]['publish_time']=date('Y-m-d', $vo['publish_time']);
            }
            return json(['data'=>$newsList,'code'=>0,'count'=>$count]);
        } else {
            return View::fetch();
        }
    }

    /**
     * 添加新闻
     */
    public function add()
    {
        if (request()->isAjax()) {
            $data=request()->param();
            $validate = Validate::rule([
                'title|新闻标题' => 'require',
                'img_src|图片' => 'require',
                'desc|简介' => 'require',
                'content|内容' => 'require',
                'publish_time|发布日期'=>'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['publish_time']=strtotime($data['publish_time']);
                $data['add_time']=time();
                $data['add_id']=Session::get('login_user_id');
                unset($data['file']);
                $add_id=Db::name('news')->insertGetId($data);
                if ($add_id) {
                    $ajaxarr=['code'=>200,'msg'=>'新闻添加成功'];
                } else {
                    $ajaxarr=['code'=>400,'msg'=>'新闻添加失败'];
                }
            }
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

    /**
     * 新闻编辑
     */
    public function edit()
    {
        $data=request()->param();
        if (request()->isAjax()) {
            $validate = Validate::rule([
                'id|新闻id'=>'require',
                'title|新闻标题' => 'require',
                'img_src|图片' => 'require',
                'desc|简介' => 'require',
                'content|内容' => 'require',
                'publish_time|发布日期'=>'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['publish_time']=strtotime($data['publish_time']);
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                unset($data['file']);
                $save_id=Db::name('news')->save($data);
                if ($save_id) {
                    $ajaxarr=['code'=>200,'msg'=>'新闻编辑成功'];
                } else {
                    $ajaxarr=['code'=>400,'msg'=>'新闻编辑失败'];
                }
            }
            return json($ajaxarr);
        } else {
            $id = isset($data['id']) ? $data['id'] : '';
            $news_data = Db::name('news')->where(['id' => $id])->find();
            $news_data['publish_time']=date('Y-m-d', $news_data['publish_time']);
            View::assign('news_data', $news_data);
            return View::fetch();
        }
    }

    /**
     * 联系我们删除
     */
    public function delete()
    {
        $data=request()->param();
        $validate = Validate::rule([
            'id|新闻ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('news')->where(['id'=>$data['id']])->save(['status'=>0,'del_time'=>time(),'del_id'=>Session::get('login_user_id')]);
            if ($save_id) {
                $ajaxarr=['code'=>200,'msg'=>'新闻删除成功'];
            } else {
                $ajaxarr=['code'=>400,'msg'=>'新闻删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
