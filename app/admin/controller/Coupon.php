<?php

/**
 * 账户管理
 * 开发者：浮生若梦
 * 开发时间：2020/9/7
 */

namespace app\admin\controller;

use app\admin\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;

class Coupon extends BaseController
{
    protected $middleware = [Check::class];
    /**
     * 菜单列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            $data = request()->param();
            $page=isset($data['page']) ? $data['page'] : '1';
            $limit=isset($data['limit']) ? $data['limit'] : '10';
            $search=isset($data['search']) ? trim($data['search']) : '';
            $where=[];
            if ($search) {
                $where[]=['title','like','%'.$search.'%'];
            }
            $where[] = ['status','<>', 'delete'];
            $userList = Db::name('coupon')->where($where)->page($page, $limit)->select()->toArray();
            foreach ($userList as $k => $vo) {
                $userList[$k]['createdTime'] = date('Y-m-d H:i:s', $vo['createdTime']);
                $userList[$k]['updatedTime'] = date('Y-m-d H:i:s', $vo['updatedTime']);
            }
            $ajaxarr=['code'=>0,'data'=>$userList];
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

    /**
     * 添加菜单
     */
    public function add()
    {
        if (request()->isAjax()) {
            $data=request()->param();
            $validate = Validate::rule([
                'title|名称' => 'require',
                'scenes|使用场景过' => 'require',
                'amount|使用场景过' => 'require',
                'limit|使用场景过' => 'require',
                'deadline|使用场景过' => 'require',
            ]);
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $parentId = 0;
                $num = $data['num'];
                unset($data['num']);
                for ($i = 0; $i<$num; $i++) {
                    $data['createdTime']=time();
                    $data['updatedTime']=time();
                    $data['parentId']=$parentId;
                    $data['code']= str_ireplace('.', '', uniqid(mt_rand(), true));
                    $add_id=Db::name('coupon')->insertGetId($data);
                    if ($i == 0) {
                        $parentId =  $add_id;
                    }
                }

                $ajaxarr=['code'=>200,'msg'=>'添加成功'];
            }
            return json($ajaxarr);
        } else {
            return View::fetch();
        }
    }

    /**
     * 上传
     */
    public function upload()
    {
        $file = request()->file('file');
        if (!Validate::fileSize($file, 1024 * 1024 * 5)) {
            $ajaxarr=['code'=>400,'msg'=>'图片过大'];
        } elseif (!Validate::fileExt($file, 'jpeg,jpg,png,gif')) {
            $ajaxarr=['code'=>400,'msg'=>'图片格式错误'];
        } else {
            $info = Filesystem::disk('public')->putFile('upload/hospital', $file);
            $img_path='/storage/'.$info;
            $ajaxarr=['code'=>0,'data'=>['src'=>$img_path]];
        }
        return json($ajaxarr);
    }

    /**
     * 菜单删除
     */
    public function delete()
    {
        $data=request()->param();
        $validate = Validate::rule([
            'id|ID'=>'require'
        ]);
        if (!$validate->check($data)) {
            $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
        } else {
            $save_id=Db::name('coupon')->where(['id'=>$data['id']])->save(['status'=>'delete','updatedTime'=>time()]);
            if ($save_id) {
                $ajaxarr=['code'=>200,'msg'=>'账户删除成功'];
            } else {
                $ajaxarr=['code'=>400,'msg'=>'账户删除失败'];
            }
        }
        return json($ajaxarr);
    }
}
