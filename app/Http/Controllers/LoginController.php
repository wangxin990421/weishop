<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Model\User;
use Illuminate\Support\Facades\Hash;  //哈希
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //登录
    public function login()
    {
        if(request()->isMethod('post') && request()->ajax()) {
            $account = request()->account;
            $u_pwd = request()->u_pwd;
            $checkemail = '/^\w+@\w+\.com$/';
            $checktel = '/^1(3|4|5|7|8)\d{9}$/';
            if (empty($account)){
                return ['code'=>5,'font'=>'账号必填'];
            }
            if(empty($u_pwd)){
                return ['code'=>5,'font'=>'密码必填'];
            }
            if(preg_match($checkemail,$account)){
                $type = 'email';
            }else if(preg_match($checktel,$account)){
                $type = 'tel';
            }
            if (Auth::attempt([$type => $account, 'password' => $u_pwd])) {
                // 认证通过...
                return ['code'=>6,'font'=>'登录成功'];
            }
        }else{
            return view('login.login');
        }
    }
    //Auth::User->id;
    //注册
    public function reg(Request $request)
    {
        if(request()->isMethod('post') && request()->ajax()){
            $emailtel = request()->emailtel;
            $emailcode = request()->emailcode;
            $emailpwd = request()->emailpwd;
            $emailrepwd = request()->emailrepwd;
            $reg = '/^\d{6,12}$/';
            $sessionInfo = $request->session()->get('emailInfo');
            $u_code = $sessionInfo['u_code'];
            $u_email = $sessionInfo['u_email'];

            if(empty($emailtel)){
                return ['code'=>5,'font'=>'账号不能为空'];exit;
            }else if($emailtel != $u_email){
                return ['code'=>5,'font'=>'发送邮件的邮箱与当前邮箱不一致'];exit;
            }
            if(empty($emailcode)){
                return ['code'=>5,'font'=>'验证码不能为空'];exit;
            }else if($emailcode != $u_code){
                return ['code'=>5,'font'=>'验证码不正确'];exit;
            }

            if(empty($emailpwd)){
                return ['code'=>5,'font'=>'密码不能为空'];exit;
            }else if(!preg_match($reg,$emailpwd)){
                return ['code'=>5,'font'=>'密码6-18位'];exit;
            }
            if(empty($emailrepwd)){
                return ['code'=>5,'font'=>'重复密码不能为空'];exit;
            }

            //注册入库
            $checkemail = '/^\w+@\w+\.com$/';
            $checktel = '/^1(3|4|5|7|8)\d{9}$/';
            if (!preg_match($checkemail,$emailtel)) {
                //手机
                $array = [
                    'tel' => $emailtel,
                    'password'=> Hash::make($emailpwd)
                ];   //自动认证加密
                $res = DB::table('users')->insert($array);
                if ($res){
                    session(['emailInfo'=>null]);
                    return ['code'=>6,'font'=>'注册成功'];
                }else{
                    return ['code'=>5,'font'=>'注册失败'];
                }

            }else{
                //邮箱
                $array = [
                    'email' => $emailtel,
                    'password'=> Hash::make($emailpwd)
                ];
                $res = DB::table('users')->insert($array);
                if ($res){
                    return ['code'=>6,'font'=>'注册成功'];
                }else{
                    return ['code'=>5,'font'=>'注册失败'];
                }
            }

        }else{
            return view('login.reg');
        }
    }

    //检测手机号唯一性
//    public function checkTel()
//    {
//        $u_tel = request()->u_tel;
//        $res = DB::table('user')->where('u_tel',$u_tel)->count();
//        //dd($res);
//        if ($res){
//            return ['code'=>5,'font'=>'该手机号已被注册'];
//        }else{
//            return ['code'=>6];
//        }
//    }

    //检测注册账号唯一性
    public function checkEmailTel()
    {
        $emailtel = request()->emailtel;
        $checkemail = '/^\w+@\w+\.com$/';
        $checktel = '/^1(3|4|5|7|8)\d{9}$/';
        if(preg_match($checkemail,$emailtel)){
            $res = DB::table('users')->where('email',$emailtel)->count();
        }else{
            $res = DB::table('users')->where('tel',$emailtel)->count();
        }
        //dd($res);
        if ($res){
            return ['code'=>5,'font'=>'该账号已被注册'];
        }else{
            return ['code'=>6];
        }
    }

    //检测邮箱唯一性
//    public function checkEmail()
//    {
//        $u_email = request()->u_email;
//        $res = DB::table('user')->where('u_email',$u_email)->count();
//        //dd($res);
//        if ($res){
//            return ['code'=>5,'font'=>'该邮箱已被注册'];
//        }else{
//            return ['code'=>6];
//        }
//    }


    //发送邮件或短信
    public function send(Request $request)
    {
        $emailtel = request()->emailtel;
        if(empty($emailtel)){
            return ['code'=>5,'font'=>'注册账号不能为空'];exit;
        }
        $checkemail = '/^\w+@\w+\.com$/';
        $checktel = '/^1(3|4|5|7|8)\d{9}$/';
        //dd(preg_match($checkemail,$emailtel));
        //生成随机6位验证码
        $code = rand(100000,999999);
        if (!preg_match($checktel,$emailtel)){
//            $res = DB::table('user')->where('u_email',$emailtel)->count();
//            //dd($res);
//            if ($res){
//                return ['code'=>5,'font'=>'该邮箱已被注册'];exit;
//            }
            //发送邮件
            Mail::send('login/emailcon',['code'=>$code],function($message)use($emailtel){
                $message->subject('欢迎注册');
                $message->to($emailtel);
            });
            $emailInfo = [
                'u_email' => $emailtel,
                'u_code' => $code,
                'send_time' => time()
            ];
            request()->session()->put('emailInfo',$emailInfo);
            return ['code'=>6,'font'=>'发送成功'];
        }else{
//            $res = DB::table('user')->where('u_tel',$emailtel)->count();
//            //dd($res);
//            if ($res){
//                return ['code'=>5,'font'=>'该手机号已被注册'];exit;
//            }
            //发送短信验证码
        $code = rand(1000,9999);
        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "19d24fa715164b58bb5a38de8b178609";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile={$emailtel}&param=code%3A{$code}&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;
        session(['code1'=>$code]);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_exec($curl);
            $emailInfo = [
                'u_email' => $emailtel,
                'u_code' => $code,
                'send_time' => time()
            ];
            request()->session()->put('emailInfo',$emailInfo);
            return ['code'=>6,'font'=>'短信验证码发送成功'];
        }
    }

    //退出
    public function logout()
    {
        \Auth::logout();
        return redirect('/');
    }

    public function test()
    {
        //dd(request()->session()->get('emailInfo.u_code'));
//        $sessionInfo = request()->session()->get('emailInfo');
//        $u_code = $sessionInfo['u_code'];
//        $u_email = $sessionInfo['u_email'];
//
//        dd($sessionInfo);
        dd(checkLogin());
    }

}
