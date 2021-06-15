<?php
namespace app\admin\model;
// 引入基础模型
use think\Model;

class Auth extends Model{
    // 设置表
    protected $table = 'tpshop_auth';
    // 设置主键
    protected $pk = 'id';

}