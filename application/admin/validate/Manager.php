<?php
namespace app\admin\validate;

use think\Validate;

class Manager extends Validate{
    protected $rule = [
        'username' => 'require|length:3,15',
        'password' => 'require|length:6,25',
        'email'    => 'email',
        'nickname' => 'require|length:3,15',
    ];

    protected $message = [
        'name.require' => '用户名必须',
        'name.max'     => '用户名3-15个字符之间',
        'password.require'     => '密码不能为空',
        'password.length'       => '密码长度必须在6-25个字符之间',
        'email'                 => '格式不正确',
        'nickname'              => '昵称必须',
    ];
}