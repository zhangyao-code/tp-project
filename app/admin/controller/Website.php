<?php

namespace app\admin\controller;

use app\admin\middleware\Check;
use app\BaseController;
use think\facade\Filesystem;
use think\facade\Request;
use think\facade\Db;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;


class Website extends BaseController
{
    protected $middleware = [Check::class];
    /**
     * 显示资源列表
     */
    public function edit()
    {
        if(request()->isAjax()){
            $data=request()->param();
            $action=isset($data['action'])?$data['action']:'organ';
            if($action == 'organ'){
                $validate = Validate::rule([
                    'organ|关于机构' => 'require',
                    'everywhere|机构遍布数' => 'require',
                    'grad_num|已结业数' => 'require',
                    'quality_num|优质教师数' => 'require',
                    'pure_num|纯名师教育数' => 'require',
                    'cooper_num|战略合作数' => 'require',
                ]);
            }elseif ($action == 'reason'){
                $validate = Validate::rule([
                    'reason|选择原因' => 'require'
                ]);
            }else{
                $validate = Validate::rule([
                    'title|网站标题' => 'require',
                    'logo|logo' => 'require',
                    'phone|电话' => 'require',
                    'email|邮箱' => 'require',
                    'qq|QQ' => 'require',
                    'post_code|邮编'=>'require',
                    'keyword|网站关键词' => 'require',
                    'description|网站描述' => 'require',
                    'copyright|版权所有' => 'require',
                ]);
            }
            if (!$validate->check($data)) {
                $ajaxarr = ['code' => 100, 'msg' => $validate->getError()];
            } else {
                $data['edit_time']=time();
                $data['edit_id']=Session::get('login_user_id');
                if(isset($data['file'])){
                   unset($data['file']);
                }
                unset($data['action']);
                $save_id=Db::name('config')->where(['id'=>1])->save($data);
                if($save_id){
                    $ajaxarr=['code'=>200,'msg'=>'编辑成功'];
                }else{
                    $ajaxarr=['code'=>400,'msg'=>'编辑失败'];
                }
            }
            return json($ajaxarr);
        }else {
            $config = Db::name('config')->where(['id' => 1])->find();
            View::assign('config', $config);
            return View::fetch();
        }
    }

    /**
     * 公共上传
     */
    public function upload(){
        $data=request()->param();
        $folder=isset($data['folder'])?$data['folder']:'avatar';
        $file = request()->file('file');
        if(!Validate::fileSize($file,1024 * 1024 * 5)){
            $ajaxarr=['code'=>400,'msg'=>'图片过大'];
        }else if(!Validate::fileExt($file,'jpeg,jpg,png,gif')){
            $ajaxarr=['code'=>400,'msg'=>'图片格式错误'];
        }else{
            $info = Filesystem::disk('public')->putFile('upload/'.$folder,$file);
            $img_path='/storage/'.$info;
            $ajaxarr=['code'=>0,'data'=>['src'=>$img_path]];
        }
        return json($ajaxarr);
    }

}
