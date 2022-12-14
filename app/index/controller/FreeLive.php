<?php

/**
 * 免费直播课
 * 开发者：浮生若梦
 * 开发时间：2020/9/10
 */

namespace app\index\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Validate;
use think\facade\View;

class FreeLive extends BaseController
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        //网站配置
        $config=Db::name('config')->where(['id'=>1])->find();
        View::assign('config', $config);
        //菜单的东西
        //课程体系二级
        $course_type=Db::name('course_type')->where('status', '<>', 0)->field('id,title')->select();
        View::assign('course_type', $course_type);
        //名师堂二级
        $teach_class=Db::name('teach_class')->where('status', '<>', 0)->field('id,title')->select();
        View::assign('teach_class', $teach_class);
        View::assign('menu_id', 3);
    }

    /**
     * 免费直播课
     */
    public function index()
    {
        //图片
        $image=Db::name('banner')->where('status', '<>', 0)->where(['type'=>2])->order('id desc')->field('id,img_src,title')->find();
        View::assign('image', $image);
        $data=request()->param();
        $search=isset($data['search']) ? $data['search'] : '';
        if ($search) {
            $where[]=['title','like','%'.$search.'%'];
        }
        $where[]=['status','<>',0];

        if (request()->isMobile()) {
            //轮播
            $banner_list=Db::name('banner')->where('status', '<>', 0)->where(['type'=>1])->field('id,img_src,title')->select();
            View::assign('banner_list', $banner_list);
            //轮播下图片
            $image_list=Db::name('banner')->where('status', '<>', 0)->where(['type'=>6])->field('id,img_src,title')->limit(3)->select();
            View::assign('image_list', $image_list);
            //直播列表
            $live_list=Db::name('free_live')->where($where)->field('id,title,speaker,img_src,desc,live_src')->order('id desc')->paginate(8, true);
            $page = $live_list->render();
            View::assign('page', $page);
            View::assign('live_list', $live_list);
            return View::fetch('/free_live/m_index');
        } else {
            //直播列表
            $live_list=Db::name('free_live')->where($where)->field('id,title,speaker,img_src,desc,live_src')->order('id desc')->paginate(6);
            $page = $live_list->render();
            View::assign('page', $page);
            View::assign('live_list', $live_list);
            return View::fetch();
        }
    }
}
