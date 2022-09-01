<?php

/**
 * 课程体系
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

class Course extends BaseController
{
    protected $middleware = [Check::class];
    /**
     * 分类列表
     */
    public function course_type(){
        if(request()->isAjax()){
            $data = request()->param();
            $page=isset($data['page'])?$data['page']:'1';
            $limit=isset($data['limit'])?$data['limit']:'10';
            $where[]=['status','<>',0];
            $count=Db::name('course_type')->where($where)->count();
            $courseTypeList=Db::name('course_type')->where($where)->page($page,$limit)->select()->toArray();
            foreach ($courseTypeList as $k=>$vo){
                $courseTypeList[$k]['add_time']=date('Y-m-d',$vo['add_time']);
            }
            return json(['data'=>$courseTypeList,'code'=>0,'count'=>$count]);
        }else{
            return View::fetch();
        }
    }

    /**
     * 添加分类
     */
    public function add_course_type(){
        if(request()->isAjax()){
            $data=request()->param();
            $validate = Validate::rule([
                'title|分类标题' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                if (Db::name('course_type')->where(['title'=>$data['title'],'status'=>1])->find()){
                    $ajaxarr=['code'=>400,'msg'=>'分类标题重复，请更换'];
                }else {
                    $data['add_time'] = time();
                    $data['add_id'] = Session::get('login_user_id');
                    $add_id = Db::name('course_type')->insertGetId($data);
                    if ($add_id) {
                        $ajaxarr = ['code' => 200, 'msg' => '课程分类添加成功'];
                    } else {
                        $ajaxarr = ['code' => 400, 'msg' => '课程分类添加失败'];
                    }
                }
            }
            return json($ajaxarr);
        }else {
            return View::fetch();
        }
    }

    /**
     * 编辑分类
     */
    public function edit_course_type(){
        $data=request()->param();
        if(request()->isAjax()){
            $validate = Validate::rule([
                'id|分类id'=>'require',
                'title|分类标题' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                if (Db::name('course_type')->where('id','<>',$data['id'])->where(['title'=>$data['title'],'status'=>1])->find()){
                    $ajaxarr=['code'=>400,'msg'=>'分类标题重复，请更换'];
                }else {
                    $data['edit_time'] = time();
                    $data['edit_id'] = Session::get('login_user_id');
                    $save_id = Db::name('course_type')->save($data);
                    if ($save_id) {
                        $ajaxarr = ['code' => 200, 'msg' => '课程分类编辑成功'];
                    } else {
                        $ajaxarr = ['code' => 400, 'msg' => '课程分类编辑失败'];
                    }
                }
            }
            return json($ajaxarr);
        }else {
            $id = isset($data['id']) ? $data['id'] : '';
            $course_type_data = Db::name('course_type')->where(['id' => $id])->field('id,title')->find();
            View::assign('course_type_data', $course_type_data);
            return View::fetch();
        }
    }

    /**
     * 删除分类
     */
    public function del_course_type(){
        $data=request()->param();
        $validate = Validate::rule([
            'id|分类ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            if(Db::name('course')->where(['type_id'=>$data['id'],'status'=>1])->find()){
                $ajaxarr = ['code' => 400, 'msg' => '分类下存在课程，请先删除课程'];
            }else {
                $save_id = Db::name('course_type')->where(['id' => $data['id']])->save(['status' => 0, 'del_time' => time(), 'del_id' => Session::get('login_user_id')]);
                if ($save_id) {
                    $ajaxarr = ['code' => 200, 'msg' => '分类删除成功'];
                } else {
                    $ajaxarr = ['code' => 400, 'msg' => '分类删除失败'];
                }
            }
        }
        return json($ajaxarr);
    }

    /**
     * 课程列表
     */
    public function course_list(){

        if(request()->isAjax()){
            $data = request()->param();
            $page=isset($data['page'])?$data['page']:'1';
            $limit=isset($data['limit'])?$data['limit']:'10';
            $type_id=isset($data['type_id'])?$data['type_id']:'';
            $where[]=['status','<>',0];
            if($type_id){
                $where[]=['type_id','=',$type_id];
            }
            $count=Db::name('course')->where($where)->count();
            $bannerList=Db::name('course')->where($where)->page($page,$limit)->select()->toArray();
            foreach ($bannerList as $k=>$vo){
                $bannerList[$k]['add_time']=date('Y-m-d',$vo['add_time']);
                $bannerList[$k]['type_title']=Db::name('course_type')->where(['id'=>$vo['type_id']])->value('title')?Db::name('course_type')->where(['id'=>$vo['type_id']])->value('title'):'';
            }
            return json(['data'=>$bannerList,'code'=>0,'count'=>$count]);
        }else{
            $type_list=Db::name('course_type')->where('status','<>',0)->select();
            View::assign('type_list',$type_list);
            return View::fetch();
        }
    }

    /**
     * 添加课程
     */
    public function add_course(){
        if(request()->isAjax()){
            $data=request()->param();
            $validate = Validate::rule([
                'title|课程标题' => 'require',
                'type_id|课程所属分类' => 'require',
                'img_src|图片'=>'require',
//                'content|内容'=>'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['add_time'] = time();
                $data['add_id'] = Session::get('login_user_id');
                unset($data['file']);
                $add_id = Db::name('course')->insertGetId($data);
                if ($add_id) {
                    $ajaxarr = ['code' => 200, 'msg' => '课程添加成功'];
                } else {
                    $ajaxarr = ['code' => 400, 'msg' => '课程添加失败'];
                }
            }
            return json($ajaxarr);
        }else {
            $type_list=Db::name('course_type')->where('status','<>',0)->select();
            View::assign('type_list',$type_list);
            return View::fetch();
        }
    }

    /**
     * 编辑课程
     */
    public function edit_course(){
        $data=request()->param();
        if(request()->isAjax()){
            $validate = Validate::rule([
                'title|课程标题' => 'require',
                'type_id|课程所属分类' => 'require',
                'img_src|图片'=>'require',
//                'content|内容'=>'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                unset($data['file']);
                $save_id=Db::name('course')->save($data);
                if($save_id){
                    $ajaxarr=['code'=>200,'msg'=>'课程编辑成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'课程编辑失败'];
                }
            }
            return json($ajaxarr);
        }else {
            $id = isset($data['id']) ? $data['id'] : '';
            $course_data = Db::name('course')->where(['id' => $id])->find();
            View::assign('course_data', $course_data);
            $type_list=Db::name('course_type')->where('status','<>',0)->select();
            View::assign('type_list',$type_list);
            return View::fetch();
        }
    }

    /**
     * 删除课程
     */
    public function delete_course(){
        $data=request()->param();
        $validate = Validate::rule([
            'id|课程ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('course')->where(['id'=>$data['id']])->save(['status'=>0,'del_time'=>time(),'del_id'=>Session::get('login_user_id')]);
            if($save_id){
                $ajaxarr=['code'=>200,'msg'=>'课程删除成功'];
            }else{
                $ajaxarr=['code'=>400,'msg'=>'课程删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
