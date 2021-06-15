<?php
// 命名空间
namespace app\admin\controller;

// 引入模型类
use think\Controller;
use think\Db;
use think\facade\Request;

class GoodsPics extends Controller
{
    // 图片上传处理方法， 
    public function upload()
    {
        // 获取上传图片的信息

        $file = Request::file('goods_logo');

        $logo = '';
        // 验证图片信息并移动到指定目录
        if ($info = $file->validate(['ext' => 'jpg,jpeg,png,gif'])->move('upload')) {
            $fileName = 'upload' . '\\' . $info->getSaveName();
            $logo = $fileName;
            return $logo;
        } else {
            // 返回错误信息
            return $file->getError();
        }
    }
    // 图片上传处理方法， 
    public function uploads()
    {
        // 获取上传图片的信息

        $files = Request::file('file');

        // 初始化路径数组, 用来记录上传成功的图片
        $path = [];
        $i = 0;

        foreach ($files as $file) {
            // 验证图片信息并移动到指定目录
            if ($info = $file->validate(['ext' => 'jpg,jpeg,png,gif'])->move('upload')) {

                $fileName = 'upload\\' . $info->getSaveName();

                // 返回文件路径
                $path[$i] = $fileName;
                $w = 400;
                $h = 400;
                $num = mt_rand(100000, 999999);
                $this->thumb($fileName, 'sma/aaa' . $i . 'a' . $num . '.png', $w, $h);

                $i++;
                // return json([1, '上传成功', 'data'=> $fileName]);
            } else {
                // 返回错误信息
                return $file->getError();
            }
        }
        // 返回上传成功的图片代号和文件名
        return $path;
    }

    // 
    public function shangchuan()
    {
        // 获取上传图片的信息

        $files = Request::file('file');

        if (!$files) {
            $this->error('上传失败, logo必须， 相册至少要一张', 'index');
        }

        // 初始化路径数组, 用来记录上传成功的图片
        $path = [];
        $i = 0;

        foreach ($files as $file) {
            if ($i == 0) {
                $lujing = 'upload\\logo';
            } else {
                $lujing = 'upload';
            }

            // 验证图片信息并移动到指定目录
            if ($info = $file->validate(['ext' => 'jpg,jpeg,png,gif'])->move($lujing)) {

                if ($i == 0) {
                    // 是logo图片
                    $fileName = 'upload\logo\\' . $info->getSaveName();
                } else {
                    // 拼接真实路径
                    $fileName = 'upload\\' . $info->getSaveName();
                }

                // 存储文件路径
                $path[$i] = $fileName;

                if ($i != 0) {

                    $num = mt_rand(100000, 999999);
                    $sma[$i] =  $this->thumb($fileName, 'sma/aaa' . $i . 'a' . $num . '.png', 400, 400);
                    $big[$i] =  $this->thumb($fileName, 'big/aaa' . $i . 'a' . $num . '.png', 800, 800);
                }
                $i++;
                // return json([1, '上传成功', 'data'=> $fileName]);
            } else {
                // 返回错误信息
                return $file->getError();
            }
        }
        $path['big'] = $big;
        $path['sma'] = $sma;
        // 返回上传成功的图片代号和文件名
        return $path;
    }

    private function thumb($filename, $inputpath, $w, $h)
    {
        // 打开图片
        $image = \think\Image::open($filename);
        $res =  $image->thumb($w, $h)->save($inputpath);
        return $inputpath;
    }

    // 查看800*800
    public function big()
    {
        $data = Request::param();
        // 通过商品id 查找大图
        $big = Db::table('tpshop_goodspics')->where('goods_id', $data['id'])->find();
        // halt($big);  得到一个用逗号连起来的数组

        $big = explode(',', $big['pics_big']);


        $this->view->big = $big;
        return $this->fetch();
    }
    // 查看400*400
    public function sma()
    {
        $data = Request::param();
        // 通过商品id 查找大图
        $big = Db::table('tpshop_goodspics')->where('goods_id', $data['id'])->find();
        // halt($big);  得到一个用逗号连起来的数组

        $big = explode(',', $big['pics_sma']);

        $this->view->big = $big;
        return $this->fetch();
    }
}
