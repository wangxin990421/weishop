<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    //用户视图
    public function user()
    {
        //查询登录信息
        $useremail = \Auth::user()->email;
        return view('user.user',['useremail'=>$useremail]);
    }
}
