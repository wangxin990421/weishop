@extends('layouts.shop')
@section('title','微商城注册')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
         @include('public.header')
     </div><!--head-top/-->
     {{--<form action="" method="" class="reg-login">--}}

      <h5>已经有账号了？点此<a class="orange" href="login">登陆</a></h5>
      <div class="lrBox">
       <div class="lrList">
           <input type="text" placeholder="输入手机号码或者邮箱号" id="emailtel"/></div>
       <div class="lrList2"><input type="text" placeholder="输入短信验证码" id="emailcode"/> <button id="sendCode">请输入验证码</button></div>
       <div class="lrList"><input type="password" placeholder="设置新密码（6-18位数字或字母）" id="emailpwd" /></div>
       <div class="lrList"><input type="password" placeholder="再次输入密码" id="emailrepwd"/></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" value="立即注册" />
      </div>
     {{--</form><!--reg-login/-->--}}
   
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     @include('public.footer')

<script>
    $(function(){
        layui.use(['form','layer'],function(){
            var form = layui.form;
            var layer = layui.layer;
            //点击获取验证码
            $('#sendCode').click(function(){
                var emailtel = $('#emailtel').val();
                //手机号正则
                var tel = /^1(3|4|5|7|8)\d{9}$/;
                //邮箱正则
                var email = /^\w+@\w+\.com$/;
                var flag = true;
                var code = '';

                if(emailtel == ''){
                        layer.msg('注册账号不能为空',{icon:5,time:2000});
                        return false;
                }else if(tel.test(emailtel) || email.test(emailtel)){
                    //检测手机号  唯一性
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method: "POST",
                        url: "reg/checktel",
                        data: {emailtel:emailtel},
                        async:false
                    }).done(function( msg ) {
                        if(msg.code == 5){
                            layer.msg(msg.font,{icon:msg.code})
                            flag = false;
                        }
                    });
                    if(flag == false){
                        return flag;
                    }

                }else{
                    layer.msg('邮箱或手机号有误',{icon:5,time:2000});
                    return false;
                }

                //倒计时
                $('#sendCode').text(60+'s').css('pointerEvents','none');
                setI = setInterval(timeless,1000);

                //发送验证码
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "reg/send",
                    data: {emailtel:emailtel},
                    async:false
                }).done(function(res){
                    layer.msg(res.font,{icon:res.code});
                    if(res.code == 5) {
                        flag = false;
                    }
                });
                if(flag == false) {
                    return false;
                }


            })
            //倒计时方法
            function timeless(){
                var _time = parseInt($('#sendCode').text());
                if(_time <= 0){
                    $('#sendCode').text('请输入验证码');
                    clearInterval(setI);
                    $('#sendCode').css('pointerEvents','auto');
                }else{
                    _time = _time-1;
                    $('#sendCode').text(_time+'s');
                }
            }

            //点击注册
            $('input[type=submit]').click(function(){
                //js验证 非空
                var emailtel = $('#emailtel').val();
                var emailcode = $('#emailcode').val();
                var emailpwd = $('#emailpwd').val();
                var emailrepwd = $('#emailrepwd').val();
                var reg = /^\d{6,18}$/;
                var flag = true;
                //手机号正则
                var tel = /^1(3|4|5|7|8)\d{9}$/;
                //邮箱正则
                var email = /^\w+@\w+\.com$/;
                if(emailtel == ''){
                    layer.msg('注册账号不能为空',{icon:5,time:2000});
                    return false;
                }else if(!tel.test(emailtel) && !email.test(emailtel)){
                        layer.msg('手机号或邮箱格式不正确',{icon:5,time:2000});
                        return false;
                }
                if(emailcode == ''){
                    layer.msg('验证码不能为空',{icon:5,time:2000});
                    return false;
                }
                if(emailpwd == ''){
                    layer.msg('密码不能为空',{icon:5,time:2000});
                    return false;
                }
                if(!reg.test(emailpwd)){
                    layer.msg('密码必须为数字6-18位',{icon:5,time:2000});
                    return false;
                }
                if(emailrepwd == ''){
                    layer.msg('重复密码不能为空',{icon:5,time:2000});
                    return false;
                }
                if(emailpwd != emailrepwd){
                    layer.msg('重复密码与密码必须一致',{icon:5,time:2000});
                    return false;
                }

                //数据传给控制器注册
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "reg",
                    data: {emailtel:emailtel,emailcode:emailcode,emailpwd:emailpwd,emailrepwd:emailrepwd},
                    async:false
                }).done(function( res ) {
                    layer.msg(res.font,{icon:res.code});
                    if(res.code == 6){
                        location.href="login";
                    }
                });

            })
        })
    })
</script>

@endsection