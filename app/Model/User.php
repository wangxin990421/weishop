<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';  //表名
    protected $primaryKey  = 'u_id';  //主键
    public $timestamps = false;  //开启自动写入时间戳
    const CREATED_AT = 'create_time';
    const UPDATED_AT = false;

    //protected $fillable = ['username','userage','photos'];  //批量赋值的字段
}
