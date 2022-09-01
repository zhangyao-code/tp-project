<?php

/**
 * 名师堂
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

class Teacher extends BaseController
{
    protected $middleware = [Check::class];
    /**
     * 名师分类列表
     */
    public function teach_class(){
        if(request()->isAjax()){
            $data = request()->param();
            $page=isset($data['page'])?$data['page']:'1';
            $limit=isset($data['limit'])?$data['limit']:'10';
            $where[]=['status','<>',0];
            $count=Db::name('teach_class')->where($where)->count();
            $teachClassList=Db::name('teach_class')->where($where)->page($page,$limit)->select()->toArray();
            foreach ($teachClassList as $k=>$vo){
                $teachClassList[$k]['add_time']=date('Y-m-d',$vo['add_time']);
            }
            return json(['data'=>$teachClassList,'code'=>0,'count'=>$count]);
        }else{
            return View::fetch();
        }
    }

    /**
     * 添加名师分类
     */
    public function add_teach_class(){
        if(request()->isAjax()){
            $data=request()->param();
            $validate = Validate::rule([
                'title|分类标题' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                if (Db::name('teach_class')->where(['title'=>$data['title'],'status'=>1])->find()){
                    $ajaxarr=['code'=>400,'msg'=>'分类标题重复，请更换'];
                }else {
                    $data['add_time'] = time();
                    $data['add_id'] = Session::get('login_user_id');
                    $add_id = Db::name('teach_class')->insertGetId($data);
                    if ($add_id) {
                        $ajaxarr = ['code' => 200, 'msg' => '名师分类添加成功'];
                    } else {
                        $ajaxarr = ['code' => 400, 'msg' => '名师分类添加失败'];
                    }
                }
            }
            return json($ajaxarr);
        }else {
            return View::fetch();
        }
    }

    /**
     * 编辑名师分类
     */
    public function edit_teach_class(){
        $data=request()->param();
        if(request()->isAjax()){
            $validate = Validate::rule([
                'id|分类id'=>'require',
                'title|分类标题' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                if (Db::name('teach_class')->where('id','<>',$data['id'])->where(['title'=>$data['title'],'status'=>1])->find()){
                    $ajaxarr=['code'=>400,'msg'=>'分类标题重复，请更换'];
                }else {
                    $data['edit_time'] = time();
                    $data['edit_id'] = Session::get('login_user_id');
                    $save_id = Db::name('teach_class')->save($data);
                    if ($save_id) {
                        $ajaxarr = ['code' => 200, 'msg' => '名师分类编辑成功'];
                    } else {
                        $ajaxarr = ['code' => 400, 'msg' => '名师分类编辑失败'];
                    }
                }
            }
            return json($ajaxarr);
        }else {
            $id = isset($data['id']) ? $data['id'] : '';
            $teach_class_data = Db::name('teach_class')->where(['id' => $id])->field('id,title')->find();
            View::assign('teach_class_data', $teach_class_data);
            return View::fetch();
        }
    }

    /**
     * 删除名师分类
     */
    public function del_teach_class(){
        $data=request()->param();
        $validate = Validate::rule([
            'id|分类ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            if(Db::name('teach')->where(['class_id'=>$data['id'],'status'=>1])->find()){
                $ajaxarr = ['code' => 400, 'msg' => '分类下存在名师，请先删除名师'];
            }else {
                $save_id = Db::name('teach_class')->where(['id' => $data['id']])->save(['status' => 0, 'del_time' => time(), 'del_id' => Session::get('login_user_id')]);
                if ($save_id) {
                    $ajaxarr = ['code' => 200, 'msg' => '名师分类删除成功'];
                } else {
                    $ajaxarr = ['code' => 400, 'msg' => '名师分类删除失败'];
                }
            }
        }
        return json($ajaxarr);
    }

    /**
     * 名师列表
     */
    public function teach_list(){

        if(request()->isAjax()){
            $data = request()->param();
            $page=isset($data['page'])?$data['page']:'1';
            $limit=isset($data['limit'])?$data['limit']:'10';
            $class_id=isset($data['class_id'])?$data['class_id']:'';
            $where[]=['status','<>',0];
            if($class_id){
                $where[]=['class_id','=',$class_id];
            }
            $count=Db::name('teach')->where($where)->count();
            $teachList=Db::name('teach')->where($where)->page($page,$limit)->select()->toArray();
            foreach ($teachList as $k=>$vo){
                $teachList[$k]['add_time']=date('Y-m-d',$vo['add_time']);
                $teachList[$k]['class_title']=Db::name('teach_class')->where(['id'=>$vo['class_id']])->value('title')?Db::name('teach_class')->where(['id'=>$vo['class_id']])->value('title'):'';
            }
            return json(['data'=>$teachList,'code'=>0,'count'=>$count]);
        }else{
            $class_list=Db::name('teach_class')->where('status','<>',0)->select();
            View::assign('class_list',$class_list);
            return View::fetch();
        }
    }

    /**
     * 添加名师
     */
    public function add_teach(){
        if(request()->isAjax()){
            $data=request()->param();
            $validate = Validate::rule([
                'title|名师名称' => 'require',
                'class_id|名师所属分类' => 'require',
                'img_src|图片'=>'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['add_time'] = time();
                $data['add_id'] = Session::get('login_user_id');
                unset($data['file']);
                $add_id = Db::name('teach')->insertGetId($data);
                if ($add_id) {
                    $ajaxarr = ['code' => 200, 'msg' => '名师添加成功'];
                } else {
                    $ajaxarr = ['code' => 400, 'msg' => '名师添加失败'];
                }
            }
            return json($ajaxarr);
        }else {
            $class_list=Db::name('teach_class')->where('status','<>',0)->select();
            View::assign('class_list',$class_list);
            return View::fetch();
        }
    }

    /**
     * 编辑名师
     */
    public function edit_teach(){
        $data=request()->param();
        if(request()->isAjax()){
            $validate = Validate::rule([
                'id|ID'=>'require',
                'title|名师名称' => 'require',
                'class_id|名师所属分类' => 'require',
                'img_src|图片'=>'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                unset($data['file']);
                $save_id=Db::name('teach')->save($data);
                if($save_id){
                    $ajaxarr=['code'=>200,'msg'=>'名师编辑成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'名师编辑失败'];
                }
            }
            return json($ajaxarr);
        }else {
            $id = isset($data['id']) ? $data['id'] : '';
            $teach_data = Db::name('teach')->where(['id' => $id])->find();
            View::assign('teach_data', $teach_data);
            $class_list=Db::name('teach_class')->where('status','<>',0)->select();
            View::assign('class_list',$class_list);
            return View::fetch();
        }
    }

    /**
     * 删除名师
     */
    public function delete_teach(){
        $data=request()->param();
        $validate = Validate::rule([
            'id|名师ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('teach')->where(['id'=>$data['id']])->save(['status'=>0,'del_time'=>time(),'del_id'=>Session::get('login_user_id')]);
            if($save_id){
                $ajaxarr=['code'=>200,'msg'=>'名师删除成功'];
            }else{
                $ajaxarr=['code'=>400,'msg'=>'名师删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
