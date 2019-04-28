@extends('layouts.shop')
@section('title','微商城首页')
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
     <form action="" method="" class="reg-login">
      <h3>还没有账号？点此<a class="orange" href="reg">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" id="account" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList"><input type="password" id="u_pwd" placeholder="输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="button" id="btn" value="立即登录"  />
      </div>
     </form><!--reg-login/-->
   
    </div><!--maincont-->
    @include('public.footer')
    <script>
     $(function() {
      layui.use(['form','layer'],function(){
       var form = layui.form;
       var layer = layui.layer;
       var flag = true;
       $('#btn').click(function() {
        var account = $('#account').val();
        var u_pwd =  $('#u_pwd').val();

        // console.log(account);
        // console.log(u_pwd);


        if (account == '') {
            layer.msg('登录账号不能为空',{icon:5});
            return false;
        }
        if (u_pwd == '') {
            // alert(111);
            layer.msg('登录密码不能为空',{icon:5});
            return false;
        }

        $.ajaxSetup({
            headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: "login",
            data: {account:account,u_pwd:u_pwd},
            async:false
        }).done(function( msg ) {
            layer.msg(msg.font,{icon:msg.code});
            if(msg.code == 6){
              location.href="/";
            }
        });

       });
      });
     })


    </script>
@endsection
