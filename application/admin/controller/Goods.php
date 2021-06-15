<?php
// 命名空间
namespace app\admin\controller;

// 引入各种类
use think\Controller;
use think\Db;
use app\admin\model\Goods as GoodsModel;
use think\Request;

class Goods extends Controller
{
    public function _empty(){
        return $this->error('操作错误', 'admin/login/logout');
    }

    public function index(Request $request)
    {
        // 初始化查找条件
        $where = '%';
        if (!empty($_GET['goods_name'])) {
            // 接收查询条件
            $data = $request->param();
            if (!$data['goods_name'] == '') {
                // 添加条件
                $where = $data['goods_name'];
            }
        }

        // 实例化模型对象
        $goods = new GoodsModel();

        // 按id降序， 每页八条数据 param1 条数， 简单分页， 传递参数
        $goods = $goods->where('goods_name', 'like', "%$where%")->order('id', 'asc')->paginate(3, false, [
            'query' => $request->param(), // 能将所有参数带上
        ]);

        // 将数据赋值给模板
        $this->view->goods = $goods;
        $this->assign('goods', $goods);

        return $this->fetch();
    }

    // 渲染插入试图
    public function add()
    {
        // 要显示的数据
        // 三个分类
        $categorys =  Db::table('tpshop_category')->field('cate_name')->select();

        $this->assign(array(
            'categorys' => $categorys,
        ));

        return $this->fetch();
    }

    // 插入 
    public function insert(Request $request)
    {
        // 如果是文件  一个方法完成win7电脑用
        if (!empty($_FILES)) {
            // 就交给图片类处理
            $pic = new \app\admin\controller\GoodsPics();
            $pic = $pic->shangchuan();
        }
        // halt($pic); // 一个数组, 多张图片
        $pic['bigs'] = '';

        foreach ($pic['big'] as $v) {
            $pic['bigs'] .= $v . ',';
        }
        $pic['bigs'] = rtrim($pic['bigs'], ',');

        $pic['smas'] = '';

        foreach ($pic['sma'] as $v) {
            $pic['smas'] .= $v . ',';
        }
        $pic['smas'] = rtrim($pic['smas'], ',');



        #####################################
        // 单张logo
        $data = $request->param();
        // 先写goods表
        $goods['goods_name']        = $data['goods_name'];
        $goods['goods_price']       = $data['goods_price'];
        $goods['goods_number']      = $data['goods_number'];
        $goods['goods_introduce']   = $data['goods_introduce'];
        $goods['goods_logo']        = $pic[0];
        $goods['create_time']       = time();

        // 验证数据
        $validate = new \app\admin\validate\Goods();
        if(!$validate->check($goods)){
            return $this->error($validate->getError()) ; 
        }

        $res = Db::table('tpshop_goods')->insertGetId($goods); // 需要返回插入id

        #######################################

        // 插入tpshop_goodspics goods_id, pics_big, pics_sma
        $goodspics['goods_id'] = $res;
        $goodspics['pics_big'] = $pic['bigs'];
        $goodspics['pics_sma'] = $pic['smas'];
        $goodspics['create_time'] = time();

        $res1 = Db::table('tpshop_goodspics')->insert($goodspics);

        if ($res && $res1) {
            $this->success('添加成功', 'index');
        } else {
            $this->error('添加失败', 'index');
        }
    }

    // 渲染更新试图
    public function edit(Request $request)
    {
        $id = $request->param();
        // 读取数据
        $goods  = Db::table('tpshop_goods')->find($id['id']);
        // 也可以用连表查询
        $pics   = Db::table('tpshop_goodspics')->where('goods_id', $goods['id'])->find();
         
        $this->view->goods = $goods;

        $pic = explode(',', $pics['pics_sma']);

        $this->view->pic  = $pic;
        
        $this->view->pics_id = $pics['id'];

        return $this->fetch();
    }

    public function update(Request $request)
    {
        $res = $request->file();

        if (!empty($res['file'])) { //  文件不为空

            // 就交给图片类处理
            $pic = new \app\admin\controller\GoodsPics();
            $pic = $pic->shangchuan();

            $pic['bigs'] = '';

            foreach ($pic['big'] as $v) {
                $pic['bigs'] .= $v . ',';
            }
            $pic['bigs'] = rtrim($pic['bigs'], ',');

            $pic['smas'] = '';

            foreach ($pic['sma'] as $v) {
                $pic['smas'] .= $v . ',';
            }
            $pic['smas'] = rtrim($pic['smas'], ',');

            // 将数据提取出来
            $logo = $pic[0]; // 获取logo路径
            // 查看文件是否存在
            $data = $request->param();
            // halt($data);  $pics_id
            $data['goods_logo'] = $logo;
            $data['update_time'] = time();
            
            $goodspics['id'] = $data['pics_id']; // 赋值后删除字段
            unset($data['pics_id']);
            // 更新数据
            $res = Db::table('tpshop_goods')->update($data);

            $goodspics['goods_id'] = $data['id'];
            $goodspics['pics_big'] = $pic['bigs'];
            $goodspics['pics_sma'] = $pic['smas'];
            $goodspics['update_time'] = time();
            
            // halt($goodspics);
            $res1 = Db::table('tpshop_goodspics')->update($goodspics);

            if ($res && $res1) {
                $this->success('更新成功', 'index');
            } else{
                $this->error('更新失败', 'index');
            }
        }

        // 当没有文件的时候
        $data = $request->param();
        $data['update_time'] = time();
        
        unset($data['pics_id']); // 将没有的字段去掉
        
        
        $res = Db::table('tpshop_goods')->update($data);
        if($res){
            $this->success('更新成功', 'index');
        }else{
            $this->error('更新失败', 'index');
        }
    }

    // 实现软删除
    public function softDelete(Request $request)
    {
        // 接收软删除id
        $id = $request->param();

        $res = GoodsModel::destroy($id['id']);

        if ($res) {
            $this->success('删除成功', 'index');
        } else {
            $this->error('删除失败', 'index');
        }
    }

    // detail 
    public function detail(Request $request)
    {
        $id = $request->param();

        // goods_name, goods_price,goods_number, goods_introduce, goods_logo

        $good = Db::table('tpshop_goods')
            ->field('id,goods_name,goods_price,goods_number, goods_introduce, goods_logo')
            ->find($id['id']);

        // 获取相册数据
        $big = Db::table('tpshop_goodspics')->where('goods_id', $id['id'])->find();
        // halt($big);  得到一个用逗号连起来的数组

        $big = explode(',', $big['pics_big']);

        $this->view->big = $big;

        $this->view->good = $good;

        return $this->fetch();
    }
}
