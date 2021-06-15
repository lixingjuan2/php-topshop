<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\facade\Session;
class Login extends Controller
{
    public function _empty(){
        return $this->error('操作错误', 'admin/login/login');
    }
    // 渲染登录页面
    public function  login()
    {

        return    $this->fetch('');
    }

    // 生成验证码
    public function verify()
    {
        // 如果gd库也开了但是就是不能正常的生成验证码可以使用ob_clean()
        //  ob_clean(); 清空缓存

        $config = [
            'fontSize'      =>  30,
            'length'        =>  4, 
        ];
        
        $verify = new \think\captcha\Captcha($config);
        return  $verify->entry();
    }

    // 登录验证
    public function doLogin(Request $request)
    {
        // 1. 接收数据
        // 2. 验证数据
        // 3. 写入session
        // 4. 跳转首页

        $captcha = $request->post('captcha');
        
        // 先验证验证码
        $verify = new \think\captcha\Captcha();
        $res = $verify->check($captcha);
        if(!$res){
            $this->error('验证码不正确');
        }
        // 接受验证码
        $username = $request->post('username');
        $password = md5($request->post('password'));

        $res = Db::table('tpshop_manager')->field('username')->where('username', $username)->find();
        if(!$res){
            $this->error('用户名错误');
        }
        $res = Db::table('tpshop_manager')->field('password')->where('password', $password)->find();
        if(!$res){
            $this->error('密码错误');
        }
        $id = Db::table('tpshop_manager')->field('id')->where('username', $username)->find();
        
        // 更新登陆时间
        Db::table('tpshop_manager')->update(['last_login_time'=>time(), 'id'=>$id['id']]);


        // 设置session
        Session::set('username', $username);
        @session_start();
        $_SESSION['username'] = $username;
        Session::set('userid', $id['id']);

        $this->success('登陆成功', 'Index/index');
    }

    // session 测试
    public function session(){
        Session::set('name', 'thinkphp');
        halt(Session::get('name'));
    }

    // 退出登录

    public function logout(){
     
        // 清除session和cookie 跳转登录页面
        // Session::delete('username');
        // Session::clear();
        @session_start();
        session_destroy();
        // cookie未设置， 暂不清理
        $this->redirect('login');

    }
}
