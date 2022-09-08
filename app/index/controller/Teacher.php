<?php

/**
 * 名师堂
 * 开发者：浮生若梦
 * 开发时间：2020/9/8
 */

namespace app\index\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Validate;
use think\facade\View;

class Teacher extends BaseController
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
        View::assign('menu_id', 5);
    }

    /**
     * 首页
     */
    public function index()
    {
        $data=request()->param();
        $image=Db::name('banner')->where('status', '<>', 0)->where(['type'=>4])->order('id desc')->field('id,img_src,title')->find();
        View::assign('image', $image);
        $search=isset($data['search']) ? $data['search'] : '';
        if ($search) {
            $where[]=['title','like','%'.$search.'%'];
        }
        $class_id=isset($data['class_id']) ? $data['class_id'] : '';
        $class_title='';
        if ($class_id) {
            $where[]=['class_id','=',$class_id];
            $class_title=Db::name('teach_class')->where(['id'=>$class_id])->value('title');
        }
        $where[]=['status','<>',0];

        if (request()->isMobile()) {
            //轮播
            $banner_list=Db::name('banner')->where('status', '<>', 0)->where(['type'=>1])->field('id,img_src,title')->select();
            View::assign('banner_list', $banner_list);
            //轮播下图片
            $image_list=Db::name('banner')->where('status', '<>', 0)->where(['type'=>6])->field('id,img_src,title')->limit(3)->select();
            View::assign('image_list', $image_list);
            //名师列表
            $teach_list=Db::name('teach')->where($where)->field('id,title,post,img_src,desc')->order('id desc')->paginate(8, true);
            $page = $teach_list->render();
            View::assign('page', $page);
            View::assign('class_id', $class_id);
            View::assign('class_title', $class_title);
            View::assign('teach_list', $teach_list);
            return View::fetch('/teacher/m_index');
        } else {
            //名师列表
            $teach_list=Db::name('teach')->where($where)->field('id,title,post,img_src,desc')->order('id desc')->paginate(6);
            $page = $teach_list->render();
            View::assign('page', $page);
            View::assign('class_id', $class_id);
            View::assign('class_title', $class_title);
            View::assign('teach_list', $teach_list);
            return View::fetch();
        }
    }

    /**
     * 详情
     */
    public function detail()
    {
        $data=request()->param();
        $id=isset($data['id']) ? $data['id'] : '';
        //课程详情
        $detail=Db::name('teach')->where(['id'=>$id])->find();
        $class_title=Db::name('teach_class')->where(['id'=>$detail['class_id']])->value('title');
        View::assign('detail', $detail);
        View::assign('class_title', $class_title);
        //上一个
        $prev_data=Db::name('teach')->where('status', '<>', 0)->where('id', '>', $id)->order('id asc')->field('id,title,img_src,post')->find();
        //下一个
        $next_data=Db::name('teach')->where('status', '<>', 0)->where('id', '<', $id)->order('id desc')->field('id,title,img_src,post')->find();
        View::assign('prev_data', $prev_data);
        View::assign('next_data', $next_data);
        if (request()->isMobile()) {
            return View::fetch('/teacher/m_detail');
        } else {
            return View::fetch();
        }
    }
}
