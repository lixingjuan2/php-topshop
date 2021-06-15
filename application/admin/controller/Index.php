<?php
// 声明命名空间
namespace app\admin\controller;

// 引入基类控制器
use think\Controller;
// 继承基类控制器
class Index extends Controller
{
    // 加载后台首页
    public function index()
    {
        return $this->fetch();
    }

    
}
