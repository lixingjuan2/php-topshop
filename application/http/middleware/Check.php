<?php

namespace app\http\middleware;

use think\facade\Session;

use think\Controller;

class Check extends Controller
{
    // 简单的进行安全处理
    public function handle($request, \Closure $next)
    {
        // 获取url
        $url = strtolower($request->url());
        // 关键词处理
        $res = strpos($url, 'insert');
        $res1 = strpos($url, 'update');
        $res2 = strpos($url, 'delete');
        $res3 = strpos($url, 'index');

        if ($res || $res1 || $res2 || $res3) {
            // 如果为真
            @session_start();
            if (!isset($_SESSION['username'])) {
                return $this->error('非法操作', 'admin/login/logout');
            }
        }

        return $next($request);
    }
}
