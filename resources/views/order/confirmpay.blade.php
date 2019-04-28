@extends('layouts.shop')
@section('title','微商城首页')
@section('content')
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>购物车</h1>
        </div>
    </header>
    <div class="head-top">
        @include('public.header')
    </div><!--head-top/-->
    <div class="dingdanlist" ">
        <table>
            @if($addressInfo)
                <tr>
                    <td>收货地址</td>
                    <td class="dingimg"></td>
                    <td align="right" width="75%"></td>
                </tr>
                @foreach($addressInfo as $k=>$v)
                    <tr>
                        <td><input type="radio" name="address_id" value="{{$v->address_id}}" @if($v->is_default == 1) checked @endif></td>
                        <td>{{$v->address_name}} {{$v->address_tel}}</td>
                        <td>{{$v->address_detail}}</td>
                    </tr>
                @endforeach
            @else

                <tr>
                    <td class="dingimg" width="75%" colspan="2">新增收货地址</td>
                    <td align="right"><a href="{{url('/addressadd')}}"><img src="{{asset('index/images/jian-new.png')}}" /></a></td>
                </tr>
            @endif

            <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
            <tr>
                <td  width="75%" colspan="2">支付方式</td>
                <td align="right"><span class="hui">支付宝</span></td>
            </tr>
            <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
            <tr>
                <td class="dingimg" width="75%" colspan="3">商品清单</td>
            </tr>
            {{--商品数据--}}
            <tbody id="goodsInfo">
            @foreach($info['cartData'] as $k=>$v)
            <tr goods_id="{{$v['goods_id']}}" class="trs">
                <td class="dingimg" width="15%"><img src="/uploads/{{$v['goods_img']}}" /></td>
                <td width="50%">
                    <h3>{{$v['goods_name']}}</h3>
                </td>
                <td align="right"><span class="qingdan">X {{$v['buy_number']}}</span></td>
            </tr>
            <p>
                <th colspan="3"><strong class="orange">¥{{$v['shop_price']}}</strong></th>
            </p>
            <tr>
                <td class="dingimg" width="75%" colspan="2">商品金额</td>
                <td align="right"><strong class="orange">¥{{$v['buy_number']*$v['shop_price']}}</strong></td>
            </tr>
            @endforeach
            </tbody>

        </table>
    </div><!--dingdanlist/-->


</div><!--content/-->

<div class="height1"></div>
<div class="gwcpiao">
    <table>
        <tr>
            <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
            <td width="50%">总计：<strong class="orange">¥{{$info['count']}}</strong></td>
            <td width="40%"><a href="javascript:;" class="jiesuan" id="submitOrder">提交订单</a></td>
        </tr>
    </table>
</div><!--gwcpiao/-->
</div><!--maincont-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

</body>
</html>
    <script>
        $(function(){
            layui.use('layer',function(){
                var layer = layui.layer;
                //点击提交订单
                $("#submitOrder").click(function(){
                    //获取商品id
                    var _tr = $('.trs');

                    var goods_id = '';
                    _tr.each(function(index){
                        goods_id += $(this).attr('goods_id') + ',';
                    });
                    goods_id = goods_id.substr(0,goods_id.length-1);

                    //获取收货信息
                    var address_id = $(':checked').val();

                    //
                    // $.post(
                    //     "{:url('Order/submitOrder')}",
                    //     {goods_id:goods_id,address_id:address_id,pay_type:pay_type,order_talk:order_talk},
                    //     function(res) {
                    //         layer.msg(res.font,{icon:res.code,time:2000},function(){
                    //             if(res.code==1){
                    //                 location.href="{:url('Order/successOrder')}?order_id="+res.order_id;
                    //             }
                    //         })
                    //     },
                    //     'json'
                    // );
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method: "POST",
                        url: "/address/submitorder",
                        data: {goods_id:goods_id,address_id:address_id},
                    }).done(function( res ) {
                        layer.msg(res.font,{icon:res.code,time:2000},function(){
                            if(res.code==6){
                                location.href = '/success/'+res.order_id;
                            }
                        });
                    });
                });

            })

        })
    </script>
@endsection