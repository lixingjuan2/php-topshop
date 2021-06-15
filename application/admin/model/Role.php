<?php
namespace app\admin\model;
// 引入基础模型
use think\Model;

use think\model\concern\SoftDelete;

class Role extends Model{

    // 引入软删除类
    use SoftDelete;

    // 设置表
    protected $table = 'tpshop_role';
    // 设置主键
    protected $pk = 'id';

        // 设置删除时间字段
        protected $deleteTime = 'delete_time';
        // 设置删除字段的默认值
        protected $defaultSoftDelete = null;
}