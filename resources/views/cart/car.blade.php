@extends('layouts.shop')
@section('title','微商城购物车表')
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

    <table class="shoucangtab">
        <tr>
            <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
            <td width="25%" align="center" style="background:#fff url({{asset('index/images/xian.jpg')}}) left center no-repeat;">
                <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
            </td>
        </tr>
    </table>
    @if($cartInfo != '')
    <div class="dingdanlist">
        <table>
            <tr>
                <td width="100%" colspan="4"><input type="checkbox" id="allbox" /> 全选</td>
            </tr>
            @foreach($cartInfo as $k=>$v)
            <tr goods_id = "{{$v->goods_id}}" goods_number="{{$v->goods_number}}">
                <td width="4%"><input type="checkbox" class="box"></td>
                <td class="dingimg" width="15%"><img src="/uploads/{{$v->goods_img}}" /></td>
                <td width="50%">
                    <h3>{{$v->goods_name}}</h3>
                    <p>商品单价：￥{{$v->shop_price}}</p>
                </td>
                <td align="right">
                    <div style="float:right">
                        {{--<button style="float:right" class="add">+</button>--}}
                        {{--<input type="text" value="{{$v->buy_number}}" style="width: 40px;float:right" class="amount buy_number"  />--}}
                        {{--<button style="float:right" class="less">-</button>--}}
                        <input style="float:left" type="button" class="car_btn_1 less" value="➖"/>
                        <input type="text" value="{{$v->buy_number}}" style="width: 40px;float:left" class="amount buy_number"  />
                        <input style="float:left" type="button" class="car_btn_2 add" value="➕"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="4"><strong class="orange">¥<span>{{$v->total}}</span></strong></th>
            </tr>
            @endforeach
        </table>
    </div><!--dingdanlist/-->
    <div class="dingdanlist">
    <table>
        <tr>
            <td width="100%" colspan="4"><a href="javascript:;"><input type="button" id="delMany" value="删除"> </a></td>
        </tr>
    </table>
    </div><!--dingdanlist/-->
    <div class="height1"></div>
    <div class="gwcpiao">
        <table>
            <tr>
                <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
                <td width="50%">总计：<strong class="orange">¥<span id="count">0</span></strong></td>
                <td width="40%"><a href="javascript:;" class="jiesuan" id="confirmOrder">去结算</a></td>
            </tr>
        </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    @endif
    <!--jq加减-->
    <script>
    $(function(){
        layui.use('layer',function(){
            var layer = layui.layer;
            //隐藏域获取商品数量
            $('#allbox').click(function (){
                //全选、全不选
                var _this= $(this);
                var status = _this.prop('checked');
                $('.box').prop('checked',status);

                //调用获取总价的方法
                countTotal();

            });

            //点击加号
            $('.add').click(function(){
                //js购买数量
                var _this = $(this);
                var buy_number = parseInt(_this.prev('input').val());
                var goods_number = _this.parents('tr').attr("goods_number");
                if (buy_number >= goods_number) {
                    _this.prop('disabled',true);
                }else{
                    buy_number = buy_number+1;
                    _this.prev('input').val(buy_number);
                    _this.parent().children('input').first().prop('disabled',false);
                }

                //调用方法使数据库或者cookie中更改购买数量
                var goods_id = _this.parents('tr').attr('goods_id');
                changeBuyNumber(goods_id,buy_number);

                //获取小计
                getSubTotal(goods_id,_this);

                //给当前复选框选中
                boxSubTotal(_this);

                //重新计算总价
                countTotal();
            })

            //点击-号
            $('.less').click(function() {
                var _this = $(this);
                var buy_number = parseInt(_this.next('input').val());
                if (buy_number <= 1) {
                    _this.prop('disabled',true);
                }else{
                    buy_number = buy_number-1;
                    _this.next('input').val(buy_number);
                    _this.parent().children('input').last().prop('disabled',false);
                }

                //控制器改变购买数量
                var goods_id = _this.parents('tr').attr('goods_id');
                changeBuyNumber(goods_id,buy_number);

                //复选框选中
                boxSubTotal(_this);

                //改变小计
                getSubTotal(goods_id,_this);

                //重新计算总价
                countTotal();
            })

            //购买数量失去焦点
            $('.buy_number').blur(function(){
                var _this = $(this);
                var buy_number = _this.val();
                var goods_number = _this.parents('tr').attr('goods_number');
                //验证
                var reg = /^\d{1,}$/;
                if (buy_number == '' || buy_number <= 1 || !reg.test(buy_number)) {
                    _this.val(1);
                } else if(parseInt(buy_number) >= parseInt(goods_number)){
                    _this.val(goods_number);
                }else{
                    _this.val(parseInt(buy_number));
                }
                //调用方法使数据库或者cookie中更改购买数量
                var goods_id = _this.parents('tr').attr('goods_id');
                changeBuyNumber(goods_id,buy_number);

                //获取小计
                getSubTotal(goods_id,_this);

                //给当前复选框选中
                boxSubTotal(_this);

                //重新计算总价
                countTotal();

            })

            //点击删除
            $('#delMany').click(function(){
                //获取选中的复选框的商品id
                var _box = $('.box');
                var goods_id = '';
                _box.each(function(index) {
                    if ($(this).prop('checked')==true) {
                        goods_id += $(this).parents('tr').attr('goods_id') + ',';
                    }
                })
                goods_id = goods_id.substr(0,goods_id.length-1);
                if(goods_id == ''){
                    layer.msg('请选择要删除的商品',{icon:5});
                    return false;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "/delcart",
                    data: {goods_id:goods_id},
                }).done(function( res ) {
                    layer.msg(res.font,{icon:res.code});
                    if (res.code == 6) {
                        //重新获取列表页面的数据
                        location.href="/cartlist";
                        //或者 把当前这一行元素删除
                    }
                });
            })

            //点击复选框
            $('.box').click(function() {
                //获取商品总价
                countTotal();
            })

            //获取总价
            function countTotal(){
                //获取所有选中的复选框 对应的商品id
                var _box = $('.box');
                var goods_id = '';
                _box.each(function(index){   //index 是获取的元素的下标
                    if ($(this).prop('checked') == true) {
                        goods_id += $(this).parents('tr').attr('goods_id') + ',';
                    }
                });
                // console.log(goods_id);  //50,65,2,3,1,
                goods_id = goods_id.substr(0,goods_id.length-1);  //50,65,2,3,1把最后的逗号截取掉

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "/counttotal",
                    data: {goods_id:goods_id},
                }).done(function( res ) {
                    $('#count').text(res);
                });
            }

            //更改购买数量
            function changeBuyNumber(goods_id,buy_number){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:"/changenum",
                    method:'post',
                    async:false,
                    data:{goods_id:goods_id,buy_number:buy_number},
                    success:function(res) {
                        //错误给出错误提示
                        if (res.code == 5) {
                            layer.msg(res.font,{icon:res.code});
                        }
                    }
                });
            }

            //给当前复选框选中
            function boxSubTotal(_this){
                _this.parents('tr').find("input[class='box']").prop('checked',true);
            }

            //获取小计
            function getSubTotal(goods_id,_this){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:"/getsubtotal",
                    method:'post',
                    data:{goods_id:goods_id},
                    success:function(res) {
                        _this.parents('tr').next('tr').find('span').text(res);
                    }
                });
            }

            //点击确认结算
            $('#confirmOrder').click(function() {
                //获取选中的商品id
                var _box = $('.box');
                var goods_id = '';
                _box.each(function(index){
                    if ($(this).prop('checked')==true) {
                        goods_id += $(this).parents('tr').attr('goods_id') + ',';
                    }
                })
                if (goods_id == '') {
                    layer.msg('请至少选择一件商品进行结算',{icon:2});
                    return false;
                }
                goods_id = goods_id.substr(0,goods_id.length-1);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:"/cart/islogin",
                    method:'post',
                    success:function(res) {
                        if(res.code == 6){
                            location.href="/confirmpay/"+goods_id;
                        }else{
                            layer.msg(res.font,{icon:res.code,time:20000});
                            location.href="/login";
                        }
                    }
                });
            })

        })
    })
    </script>
    </body>
    </html>
@endsection