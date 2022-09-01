<?php

/**
 * 战略合作管理
 * 开发者：浮生若梦
 * 开发时间：2020/9/9
 */

namespace app\admin\controller;

use app\admin\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;

class Cooper extends BaseController
{

    protected $middleware = [Check::class];
    /**
     * 战略合作列表
     */
    public function index()
    {
        if(request()->isAjax()){
            $data = request()->param();
            $page=isset($data['page'])?$data['page']:'1';
            $limit=isset($data['limit'])?$data['limit']:'10';
            $search=isset($data['search'])?$data['search']:'';
            if($search){
                $where[]=['title','like','%'.$search.'%'];
            }
            $where[]=['status','<>',0];
            $count=Db::name('cooper')->where($where)->count();
            $cooperList=Db::name('cooper')->where($where)->page($page,$limit)->select()->toArray();
            foreach ($cooperList as $k=>$vo){
                $cooperList[$k]['add_time']=date('Y-m-d',$vo['add_time']);
            }
            return json(['data'=>$cooperList,'code'=>0,'count'=>$count]);
        }else{
            return View::fetch();
        }
    }

    /**
     * 添加战略合作
     */
    public function add(){
        if(request()->isAjax()){
            $data=request()->param();
            $validate = Validate::rule([
                'title|战略合作标题' => 'require',
                'img_src|图片' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['add_time']=time();
                $data['add_id']=Session::get('login_user_id');
                unset($data['file']);
                $add_id=Db::name('cooper')->insertGetId($data);
                if($add_id){
                    $ajaxarr=['code'=>200,'msg'=>'战略合作添加成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'战略合作添加失败'];
                }
            }
            return json($ajaxarr);
        }else {

            return View::fetch();
        }
    }

    /**
     * 新闻编辑
     */
    public function edit(){
        $data=request()->param();
        if(request()->isAjax()){
            $validate = Validate::rule([
                'id|新闻id'=>'require',
                'title|战略合作标题' => 'require',
                'img_src|图片' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                unset($data['file']);
                $save_id=Db::name('cooper')->save($data);
                if($save_id){
                    $ajaxarr=['code'=>200,'msg'=>'战略合作编辑成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'战略合作编辑失败'];
                }
            }
            return json($ajaxarr);
        }else {
            $id = isset($data['id']) ? $data['id'] : '';
            $cooper_data = Db::name('cooper')->where(['id' => $id])->find();
            View::assign('cooper_data', $cooper_data);
            return View::fetch();
        }
    }

    /**
     * 战略合作删除
     */
    public function delete(){
        $data=request()->param();
        $validate = Validate::rule([
            'id|战略合作ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('cooper')->where(['id'=>$data['id']])->save(['status'=>0,'del_time'=>time(),'del_id'=>Session::get('login_user_id')]);
            if($save_id){
                $ajaxarr=['code'=>200,'msg'=>'战略合作删除成功'];
            }else{
                $ajaxarr=['code'=>400,'msg'=>'战略合作删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
