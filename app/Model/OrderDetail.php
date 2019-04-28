<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_detail';  //表名
    protected $primaryKey  = 'id';  //主键
    public $timestamps = false;  //开启自动写入时间戳

    public $fillable = ['goods_id','goods_name','goods_img','shop_price','buy_number','order_id','user_id'];
}
