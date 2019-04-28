@extends('layouts.shop')
@section('title','用户首页')
@section('content')
    <div class="userName">
        <dl class="names">
            <dd>
                <h3>欢迎 <sapn style="color:red">{{$useremail}}</sapn> 登录</h3>
            </dd>
            <div class="clearfix"></div>
        </dl>
        <div class="shouyi">

        </div><!--shouyi/-->
    </div><!--userName/-->

    <ul class="userNav">
        <li><span class="glyphicon glyphicon-list-alt"></span><a href="/orderlist">我的订单</a></li>
        <div class="height2"></div>

        <li><span class="glyphicon glyphicon-map-marker"></span><a href="{{url('address/addresslist')}}">收货地址管理</a></li>
    </ul><!--userNav/-->

    <div class="lrSub">
        <a href="{{url('/user/logout')}}">退出登录</a>
    </div>

    @include('public.footer')


</body>
</html>
@endsection