<?php

namespace app\admin\controller;

// 引入模型类
use app\admin\model\Role as RoleModel;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Auth;

class Role extends Controller
{
    public function index()
    {
        // 获取相关数据和分页

        $role = new RoleModel();

        $roles = $role->paginate(3);

        $this->view->roles = $roles;

        return $this->fetch();
    }

    public function add()
    {
        // 先把auth 全部显示出来
        $auths = Auth::all();

        $this->view->auths = $auths;

        return $this->fetch();
    }

    public function insert(Request $request)
    {
        $data = $request->param();

        // 讲获取到的权限id拼接起来， 中间用逗号隔开
        $auths  =   $data['role_auth_ids'];
        $auth_ids = '';
        foreach ($auths as $auth) {
            $auth_ids .= $auth . ',';
        }
        // 去掉最右边的逗号
        $auth_ids = rtrim($auth_ids, ',');
        $data['role_auth_ids'] = $auth_ids;
        $data['create_time'] = time();

        // 实例化模型讲数据插入进去或者Db

        $res = Db::table('tpshop_role')->insert($data);

        if ($res) {
            $this->success('添加成功', 'index');
        } else {
            $this->success('添加失败', 'index');
        }
    }

    public function edit(Request $request)
    {
        // 获取id
        $id = $request->param();
        // 通过Id获取全部数据
        $role = Db::table('tpshop_role')->find($id['id']);

        // 数组分割
        // $role = explode(',', $role['role_auth_ids']);
        $quanxian = $role['role_auth_ids'];
        // $quanxian = explode(',', $role['role_auth_ids']);

        // 先把auth 全部显示出来
        $auths = Auth::all();

        $this->view->auths = $auths;
        $this->view->quanxian = $quanxian;
        // 视图赋值
        $this->view->role = $role;
        // 渲染页面
        return $this->fetch();
    }
    public function update(Request $request)
    {
        $data = $request->param();
        

        $auths  =   $data['role_auth_ids'];
        $auth_ids = '';
        foreach ($auths as $auth) {
            $auth_ids .= $auth . ',';
        }
        // 去掉最右边的逗号
        $auth_ids = rtrim($auth_ids, ',');
        $data['role_auth_ids'] = $auth_ids;
        $data['update_time'] = time();

        // 实例化模型讲数据插入进去或者Db

        $res = Db::table('tpshop_role')->update($data);

        if ($res) {
            $this->success('分配成功', 'index');
        } else {
            $this->success('分配失败', 'index');
        }
    }

    // 实现软删除
    public function softDelete(Request $request){
        $id = $request->param();
        
        $res = RoleModel::destroy($id['id']);
        if ($res) {
            $this->success('删除成功', 'index');
        } else {
            $this->error('删除失败', 'index');
        }
    }

}
