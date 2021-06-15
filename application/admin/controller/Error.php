<?php
namespace app\admin\controller;

use think\Request;

use think\Controller;

class Error extends Controller{
    public function index(){
        return $this->error('操作失败', 'admin/login/login');
    }
}