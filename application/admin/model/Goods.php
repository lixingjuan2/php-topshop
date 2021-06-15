<?php
namespace app\admin\model;
// 引入基础模型
use think\Model;
// 引入软删除类
use think\model\concern\SoftDelete;

class Goods extends Model{
    // trait
    use SoftDelete;
    // 设置表
    protected $table = 'tpshop_goods';
    // 设置主键
    protected $pk = 'id';

    // 设置软删除字段
    protected $deleteTime = 'delete_time';
    // 设置该字段的默认值
    protected $defaultSoftDelete = null;

}