<?php
/**
 * 请求中间件
 */

namespace app\api\middleware;

use think\facade\Db;
use think\facade\Session;
use think\response\Html;

class Check
{
    public function handle($request, \Closure $next)
    {
        $token = $request->header('token');

        if (empty($token)) {
            return json(['code'=> 403, 'message'=> 'Authentication error, header->token is empty']);
        }
        $user =Db::name('user_session')->where(['token'=>$token])->find();
        if (empty($user)) {
            return json(['code'=> 403, 'message'=> 'Authentication error, header->token is error！']);
        }
        if ($user['alive_before'] < time()) {
            return json(['code'=> 403, 'message'=> 'Authentication error, header->token expired！']);
        }

        return $next($request);
    }
}
