<?php
namespace app\admin\validate;

use think\Validate;

class Goods extends Validate{
    protected $rule = [
        'goods_name' => 'require|length:3,225|chsAlphaNum',
        'goods_price' => 'require|number',
        'goods_number'    => 'require',
        'goods_introduce' => 'require|min:5',
    ];

    protected $message = [
        'goods_name.require' => '商品名称必须',
       
    ];

    
}