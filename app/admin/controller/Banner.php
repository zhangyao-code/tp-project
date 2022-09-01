<?php

/**
 * 轮播管理
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

class Banner extends BaseController
{
    protected $middleware = [Check::class];

    /**
     * 轮播列表
     */
    public function index()
    {
        $banner_type=['首页轮播','免费直播课','实力保证','名师堂','联系我们','首页轮播下图片','首页遍布图'];

        if(request()->isAjax()){
            $data = request()->param();
            $page=isset($data['page'])?$data['page']:'1';
            $limit=isset($data['limit'])?$data['limit']:'10';
            $type=isset($data['type'])?$data['type']:'';
            $where[]=['status','<>',0];
            if($type){
                $where[]=['type','=',$type];
            }
            $count=Db::name('banner')->where($where)->count();
            $bannerList=Db::name('banner')->where($where)->page($page,$limit)->select()->toArray();
            foreach ($bannerList as $k=>$vo){
                $bannerList[$k]['add_time']=date('Y-m-d',$vo['add_time']);
                $bannerList[$k]['type_title']=$banner_type[$vo['type']-1];
            }
            return json(['data'=>$bannerList,'code'=>0,'count'=>$count]);
        }else{
            return View::fetch();
        }
    }

    /**
     * 添加轮播
     */
    public function add(){
        if(request()->isAjax()){
            $data=request()->param();
            $validate = Validate::rule([
                'img_src|图片链接' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['add_time']=time();
                $data['add_id']=Session::get('login_user_id');
                unset($data['file']);
                $add_id=Db::name('banner')->insertGetId($data);
                if($add_id){
                    $ajaxarr=['code'=>200,'msg'=>'轮播添加成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'轮播添加失败'];
                }
            }
            return json($ajaxarr);
        }else {

            return View::fetch();
        }
    }

    /**
     * 轮播编辑
     */
    public function edit(){
        $data=request()->param();
        if(request()->isAjax()){
            $validate = Validate::rule([
                'img_src|图片链接' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                unset($data['file']);
                $save_id=Db::name('banner')->save($data);
                if($save_id){
                    $ajaxarr=['code'=>200,'msg'=>'轮播编辑成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'轮播编辑失败'];
                }
            }
            return json($ajaxarr);
        }else {
            $id = isset($data['id']) ? $data['id'] : '';
            $banner_data = Db::name('banner')->where(['id' => $id])->field('id,title,img_src,type')->find();
            View::assign('banner_data', $banner_data);
            return View::fetch();
        }
    }

    /**
     * 轮播删除
     */
    public function delete(){
        $data=request()->param();
        $validate = Validate::rule([
            'id|轮播ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('banner')->where(['id'=>$data['id']])->save(['status'=>0,'del_time'=>time(),'del_id'=>Session::get('login_user_id')]);
            if($save_id){
                $ajaxarr=['code'=>200,'msg'=>'轮播删除成功'];
            }else{
                $ajaxarr=['code'=>400,'msg'=>'轮播删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
