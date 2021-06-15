<?php
namespace app\admin\model;
// 引入基础模型
use think\Model;

class User extends Model{
    // 设置表
    protected $table = 'tpshop_user';
    // 设置主键
    protected $pk = 'id';

}