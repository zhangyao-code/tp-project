<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;
use think\facade\View;

class Course extends BaseController
{
//    protected $middleware = [Check::class];

    public function index()
    {
      file_put_contents('../../test.log',$this->request->param());
        return json(['dsfsd'=>'asdf']);
    }



}
