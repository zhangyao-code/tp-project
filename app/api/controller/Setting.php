<?php

namespace app\api\controller;

use app\api\middleware\Check;
use app\BaseController;
use think\facade\Db;

class Setting extends BaseController
{
    protected $middleware = [Check::class];

    public function about(): \think\response\Json
    {
        $tableData = Db::name('setting')->where(['code' => 'aboutSetting'])->find();
        return json(['code'=>200,'data'=>empty($tableData) ? '' : $tableData['content']]);
    }
}
