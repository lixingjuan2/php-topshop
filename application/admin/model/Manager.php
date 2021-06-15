<?php

namespace app\admin\model;
// 引入基础模型
use think\Model;
use think\model\concern\SoftDelete;


class Manager extends Model
{

    // 引入软删除类
    use SoftDelete;

    // 设置表
    protected $table = 'tpshop_manager';
    // 设置主键
    protected $pk = 'id';

    /**
     * 实现软删除功能的步骤
     * 1.在数据表中新增delete_time字段(字段名可自定义)
     * 2.在模型中导入trait类:SoftDelete
     * 3.设置模型属性 protected $deleteTime = '删除时间字段名';
     * 4.设置软删除字段默认值[可选]
     */
    //使用软删除功能,必须先导入model/concern/SoftDelete.php
    // use think\model\concern\SoftDelete;    //实际上一个trait方法集

    // 设置删除时间字段
    protected $deleteTime = 'delete_time';
    // 设置删除字段的默认值
    protected $defaultSoftDelete = null;
}
