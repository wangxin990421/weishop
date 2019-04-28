@extends('layouts.shop')
@section('title','微商城首页')
@section('content')
     <div class="head-top">
      @include('public.header')
     </div><!--head-top/-->
     <form action="/prolist" method="post" class="search">
       @csrf
      <input type="text" class="seaText fl" name="searchname"/>
      <input type="submit" value="搜索" class="seaSub fr" />
     </form><!--search/-->
     <ul class="reg-login-click">
      <li><a href="/login">登录</a></li>
      <li><a href="/reg" class="rlbg">注册</a></li>
      <div class="clearfix"></div>
     </ul><!--reg-login-click/-->
     <div id="sliderA" class="slider">
         @foreach($priceInfo as $k=>$v)
            <a href="/proinfo/{{$v->goods_id}}"><img src="/uploads/{{$v->goods_img}}"/></a>
         @endforeach
     </div><!--sliderA/-->
     <ul class="pronav">
         @foreach($cateInfo as $k=>$v)
             <li><a href="/prolist/{{$v->cate_id}}">{{$v->cate_name}}</a></li>
         @endforeach
         <div class="clearfix"></div>
     </ul><!--pronav/-->
     <div class="index-pro1">
      @foreach($proInfo as $k=>$v)
      <div class="index-pro1-list">
       <dl>
        <dt><a href="/proinfo/{{$v->goods_id}}"><img src="/uploads/{{$v->goods_img}}"/></a></dt>
        <dd class="ip-text"><a href="/proinfo/{{$v->goods_id}}">{{$v->goods_name}}</a></dd>
        <dd class="ip-price"><strong>¥{{$v->shop_price}}</strong> <span>¥{{$v->market_price}}</span></dd>
       </dl>
      </div>
      @endforeach
      <div class="clearfix"></div>
     </div><!--index-pro1/-->

    
    </div><!--maincont-->
     @include('public.footer')
@endsection