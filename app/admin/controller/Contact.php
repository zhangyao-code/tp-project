<?php

/**
 * 联系我们
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

class Contact extends BaseController
{

    protected $middleware = [Check::class];
    /**
     * 联系我们列表
     */
    public function index()
    {
        if(request()->isAjax()){
            $data = request()->param();
            $page=isset($data['page'])?$data['page']:'1';
            $limit=isset($data['limit'])?$data['limit']:'10';
            $search=isset($data['search'])?$data['search']:'';
            if($search){
                $where[]=['title|en_title|address|phone','like','%'.$search.'%'];
            }
            $where[]=['status','<>',0];
            $count=Db::name('contact')->where($where)->count();
            $contactList=Db::name('contact')->where($where)->page($page,$limit)->select()->toArray();
            foreach ($contactList as $k=>$vo){
                $contactList[$k]['add_time']=date('Y-m-d',$vo['add_time']);
            }
            return json(['data'=>$contactList,'code'=>0,'count'=>$count]);
        }else{
            return View::fetch();
        }
    }

    /**
     * 添加联系我们
     */
    public function add(){
        if(request()->isAjax()){
            $data=request()->param();
            $validate = Validate::rule([
                'title|公司名称' => 'require',
                'en_title|英文名称' => 'require',
                'address|地址' => 'require',
                'phone|电话' => 'require',
                'email|邮箱' => 'require',
                'post_code|邮政编码' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['add_time']=time();
                $data['add_id']=Session::get('login_user_id');
                $add_id=Db::name('contact')->insertGetId($data);
                if($add_id){
                    $ajaxarr=['code'=>200,'msg'=>'联系我们添加成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'联系我们添加失败'];
                }
            }
            return json($ajaxarr);
        }else {

            return View::fetch();
        }
    }

    /**
     * 联系我们编辑
     */
    public function edit(){
        $data=request()->param();
        if(request()->isAjax()){
            $validate = Validate::rule([
                'id|联系我们ID'=>'require',
                'title|公司名称' => 'require',
                'en_title|英文名称' => 'require',
                'address|地址' => 'require',
                'phone|电话' => 'require',
                'email|邮箱' => 'require',
                'post_code|邮政编码' => 'require'
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                $save_id=Db::name('contact')->save($data);
                if($save_id){
                    $ajaxarr=['code'=>200,'msg'=>'联系我们编辑成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'联系我们编辑失败'];
                }
            }
            return json($ajaxarr);
        }else {
            $id = isset($data['id']) ? $data['id'] : '';
            $contact_data = Db::name('contact')->where(['id' => $id])->find();
            View::assign('contact_data', $contact_data);
            return View::fetch();
        }
    }

    /**
     * 联系我们删除
     */
    public function delete(){
        $data=request()->param();
        $validate = Validate::rule([
            'id|联系我们ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('contact')->where(['id'=>$data['id']])->save(['status'=>0,'del_time'=>time(),'del_id'=>Session::get('login_user_id')]);
            if($save_id){
                $ajaxarr=['code'=>200,'msg'=>'联系我们删除成功'];
            }else{
                $ajaxarr=['code'=>400,'msg'=>'联系我们删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
