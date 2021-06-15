<?php

namespace app\admin\controller;

// 引入模型类
use app\admin\model\Manager as ManagerModel;
use think\Controller;
use think\Request;
use think\Db;
use think\facade\Session;

class Manager extends Controller
{
    public function _empty(){
        return $this->error('操作错误', 'admin/login/logout');
    }
    public function index(Request $request)
    {
        //设置查找变量
        $where = '%';
        if (!empty($_GET['username'])) {
            // 接收查询条件
            $data = $request->param();
            if (!$data['username'] == '') {

                $where = $data['username'];
            }
        }

        // 实例化模型对象
        $manager = new ManagerModel();

        // 按id降序， 每页八条数据
        $managers = $manager->where('username', 'like', "%$where%")->order('id', 'asc')->paginate(3, false,[
            'query' => $request->param(), // 能将所有参数带上
        ]);

        // 将数据赋值给模板
        $this->assign('managers', $managers);
        return $this->fetch();
    }

    // 渲染模板
    public function add()
    {
        return $this->fetch();
    }

    // 新增方法
    public function insert(Request $request)
    {
        // 接受数据
        $data = $request->param();
        // 对获取的数据进行验证
         $validate = new \app\admin\validate\Manager;
         if(!$validate->check($data)){
           return $this->error($validate->getError(), 'add');
         }


        // 密码乣加密
        $data['password'] = md5($data['password']);
        $data['create_time'] = time();
        // 验证数据

        // 实例化模型并插入数据
        $manager = new ManagerModel();

        $res = $manager->save($data);

        if ($res) {
            $this->success('新增成功', 'manager/index');
        } else {
            $this->error('新增失败', 'manager/add');
        }
    }

    // 真删除
    public function delete(Request $request)
    {
        $id = $request->param();
        // 实例化模型
        $res = ManagerModel::get($id['id']);
        $res = $res->delete();

        if ($res) {
            $this->success('删除成功', 'manager/index');
        } else {
            $this->error('删除失败', 'manager/index');
        }
    }

    // 调用编辑视图
    public function edit(Request $request)
    {
        // 通过id将用户的信息显示出来
        $data = $request->get();

        // 只获取id, username, nickname, email, 字段
        $res = Db::table('tpshop_manager')->field('id,username, nickname, email')->get($data['id']);

        // 将数据渲染到模板
        $this->assign('manager', $res);

        return $this->fetch();
    }

    public function update(Request $request)
    {
        // 将获取后的数据验证后进行保存, 成功后跳转列表页
        $data = $request->post();
        $data['update_time'] = time();

        // 进行保存
        $res = ManagerModel::update($data);

        if ($res) {
            $this->success('保存成功', 'index');
        } else {
            $this->error('保存失败', 'index');
        }
    }

    // 重置密码
    public function resetPass(Request $request)
    {
        // 通过id找到此人并重置密码123456
        $data = $request->param();
        $data['password'] = md5('123456');

        $res = ManagerModel::update($data);

        if ($res) {
            $this->success('密码重置成功', 'index');
        } else {
            $this->error('密码重置失败', 'index');
        }
    }

    // 
    public function editpass(){
        // 通过id找到此人信息
        $id =  Session::get('userid');
        $manager = new ManagerModel();
        $manager = $manager->get($id);
        $this->assign('manager', $manager);
        return $this->fetch();
    }

    // 修改密码
    public function updatepass(Request $request){
        $data = $request->post();
        
        $data['password'] = md5($data['password']);

        $res = ManagerModel::update($data);

        if ($res) {
            $this->success('密码修改成功', 'index');
        } else {
            $this->error('密码修改失败', 'index');
        }
    }

    // 实现软删除
    public function softDelete(Request $request){
        $id = $request->param();
        
        $res = ManagerModel::destroy($id['id']);
        if ($res) {
            $this->success('删除成功', 'index');
        } else {
            $this->error('删除失败', 'index');
        }
    }
}
