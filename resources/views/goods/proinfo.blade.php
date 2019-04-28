@extends('layouts.shop')
@section('title','微商城商品详情页')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div >
         <img src="/uploads/{{$goodsInfo->goods_img}}"/>
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange">{{$goodsInfo->shop_price}}</strong></th>
       <td>
           {{--点击加减号--}}
           <div>
               <input type="hidden" id="goods_id" value="{{$goodsInfo->goods_id}}">
               <button style="float:right" class="add">+</button>
               <input type="text" value="1" style="width: 40px;float:right" class="amount" id="buy_number"/>
               <button style="float:right" class="less">-</button>
           </div>
       </td>
      </tr>
      <tr>
       <td>
        <strong>{{$goodsInfo->goods_name}}</strong>
        <p class="hui">库存<span id="goods_number">{{$goodsInfo->goods_number}}</span></p>
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
       </td>
      </tr>
     </table>

     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      {{$goodsInfo->description}}
     </div><!--proinfoList/-->
     <table class="jrgwc">
      <tr>
       <th>
        <a href="/"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td><a href="javascript:;" id="addCart">加入购物车</a></td>
      </tr>
     </table>
    </div><!--maincont-->
     <!--jq加减-->
  </body>
</html>
     <script>
         $(function() {
             layui.use('layer',function() {
                 var layer = layui.layer;
                 //获取库存
                 var goods_number = $('#goods_number').text();

                 //点击加号
                 $('.add').click(function(){
                     var _this = $(this);
                     var buy_number = parseInt($('#buy_number').val());

                     //判断是否大于库存
                     if (buy_number >= goods_number) {
                         //➕失效
                         _this.prop('disabled',true);
                     }else{
                         buy_number = buy_number+1;
                         $('#buy_number').val(buy_number);
                         //-生效
                         _this.next().next().prop('disabled',false);
                     }
                 });

                 //点击减号
                 $('.less').click(function(){
                     var _this = $(this);
                     var buy_number = parseInt($('#buy_number').val());

                     //判断是否小于1
                     if (buy_number <= 1) {
                         //-失效
                         _this.prop('disabled',true);
                     }else{
                         buy_number = buy_number-1;
                         $('#buy_number').val(buy_number);
                         //+生效
                         _this.prev().prev().prop('disabled',false);
                     }
                 });

                 //失去焦点
                 $('#buy_number').blur(function() {
                     var _this = $(this);
                     //获取文本框的值
                     var buy_number = _this.val();
                     //验证
                     var reg = /^\d{1,}$/;
                     if (buy_number == '' || buy_number <= 1 || !reg.test(buy_number)) {
                         _this.val(1);
                     } else if(parseInt(buy_number) >= parseInt(goods_number)){
                         _this.val(goods_number);
                     }else{
                         _this.val(parseInt(buy_number));
                     }

                 });

                 //点击加入购物车
                 $('#addCart').click(function(){
                     var buy_number = $('#buy_number').val();
                     var goods_id  = $('#goods_id').val();

                     //验证
                     if (buy_number == '') {
                         layer.msg('请选择购买数量',{icon:5});
                         return false;
                     }
                     if (goods_id == '') {
                         layer.msg('请选择一个商品',{icon:5});
                         return false;
                     }

                     $.ajaxSetup({
                         headers: {
                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                         }
                     });
                     $.ajax({
                         method: "POST",
                         url: "/addcart",
                         data: {goods_id:goods_id,buy_number:buy_number},
                         async:false
                     }).done(function( res ) {
                         if (res.code == 6) {
                             layer.confirm('是否进入购物车进行结算',function(){
                                 location.href="/cartlist";
                             });
                         } else{
                             layer.msg(res.font,{icon:res.code,time:2000},function(){
                                 location.href="/login";
                             });
                         }
                     });
                 });
             })
         })
     </script>
@endsection