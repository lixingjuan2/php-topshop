<?php
namespace app\admin\model;
// 引入基础模型
use think\Model;


class Category extends Model{
    // 设置表
    protected $table = 'tpshop_category';
    // 设置主键
    protected $pk = 'id';

    // 分类方法
    // 将一个乱的数据排成一个目标数组
    public function category(){

    }

}