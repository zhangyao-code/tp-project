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

    public function bill(): \think\response\Json
    {
        $tableData = Db::name('setting')->where(['code' => 'billRuleSetting'])->find();
        return json(['code'=>200,'data'=>empty($tableData) ? '' : $tableData['content']]);
    }

    public function relationship(): \think\response\Json
    {
        $tableData = Db::name('setting')->where(['code' => 'relationship'])->find();
        return json(['code'=>200,'data'=>empty($tableData) ? [] : array_filter(explode('，', $tableData['content'])) ]);
    }

    public function carousel(): \think\response\Json
    {
        $tableData = Db::name('setting')->where(['code' => 'carouselSetting'])->find();
        $data = array_filter(json_decode($tableData['content'], true));
        foreach ($data as &$value){
            $value = $this->host.$value;
        }
        return json(['code'=>200,'data'=>empty($data) ? [] : array_values($data) ]);

    }
}
