@extends('layouts.shop')
@section('title','微商城商品列表页')
@section('content')
    <!-- 引入分页样式 -->
    <link rel="stylesheet" href="{{asset('css/page.css')}}">

     <ul class="pronav">
         <form action="javascript:;" method="" class="search">
             <input type="hidden" value="{{$cate_id}}" id="cate_id">
             <input type="text" class="seaText fl" id="search" value="{{$searchname}}"/>
             <input type="button" value="搜索" id="sousuo" class="seaSub fr" />
         </form>
      <div class="clearfix"></div>
     </ul><!--pronav/-->
     <ul class="pro-select">
      <li class="pro-selCur default" is_type="1"><a href="javascript:;" >新品</a></li>
     <li class="default" is_type="2" field="goods_number"><a href="javascript:;">库存<span>↑</span></a></li>
      <li class="default" is_type="3" field="shop_price"><a href="javascript:;">价格<span>↑</span></a></li>
     </ul><!--pro-select/-->
     <div class="prolist" id="show">
     @foreach($goodsInfo as $k=>$v)
      <dl >
       <dt><a href="/proinfo/{{$v->goods_id}}"><img src="/uploads/{{$v->goods_img}}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="/proinfo/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
        <div class="prolist-price"><strong>¥{{$v->shop_price}}</strong> <span>¥{{$v->market_price}}</span></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
     @endforeach

     </div><!--prolist/-->
     @include('public.footer')
    <script>
        $(function(){
            layui.use('layer',function(){
               var layer = layui.layer;
               var floor = 1;
                $(document).on('click','.default',function(){
                    //点击时，变为红色背景色，兄弟节点移除该样式类
                    var _this = $(this);
                    _this.addClass('pro-selCur');
                    _this.siblings('li').removeClass('pro-selCur');

                    //获取类型判断is_type判断是点击的库存、价格或者新品，做特效
                    var is_type = _this.attr('is_type');
                    var flag = _this.find('span').text();

                    if (is_type == 2 || is_type== 3) {
                        //2点击的价格或者库存
                        if (flag == '↑') {
                            _this.find('span').text('↓');
                        } else {
                            _this.find('span').text('↑');
                        }
                    }

                    getData();
                    floor = 1;
                });

                //点击搜索
                $('#sousuo').click(function(){
                    //重新获取商品数据
                    getData();
                    floor = 1;
                });

               //获取所有条件替换数据
                function getData(floor = 1){
                    //获取所有条件
                    var cate_id = $('#cate_id').val();
                    var searchdata = $('#search').val();
                    var _default = $('.pro-selCur.default');
                    var is_type = _default.attr('is_type');

                     if(is_type == 2 || is_type == 3){
                        var field = _default.attr('field');  //库存和价格对应的字段
                        var flag = _default.find('span').text();
                        if (flag == '↑') {
                            var type = 'asc';
                        } else{
                            var type = 'desc';
                        }
                    }else if(is_type == 1){
                         var field = 'goods_id';
                         var type = 'desc';
                     }

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method: "POST",
                        url: "",
                        data: {cate_id:cate_id,searchdata:searchdata,is_type:is_type,field:field,type:type,floor:floor},
                    }).done(function( res ) {
                        if(res != 0){
                            if(floor == 1){
                                $('#show').html(res);
                            }else{
                                $('#show').append(res);
                            }
                        }else{
                            layer.msg('无数据',{icon:5});
                        }
                    });

                }

                //滚动事件触发
                window.onscroll = function () {
                    if (getScrollTop() + getClientHeight() === getScrollHeight()) {
                        floor++;
                        getData(floor);
                    }
                };

                //--------------上拉加载更多---------------
                //获取滚动条当前的位置
                function getScrollTop() {
                    var scrollTop = 0;
                    if (document.documentElement && document.documentElement.scrollTop) {
                        scrollTop = document.documentElement.scrollTop;
                    } else if (document.body) {
                        scrollTop = document.body.scrollTop;
                    }
                    return scrollTop;
                }

                //获取当前可视范围的高度
                function getClientHeight() {
                    var clientHeight = 0;
                    if (document.body.clientHeight && document.documentElement.clientHeight) {
                        clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight);
                    } else {
                        clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight);
                    }
                    return clientHeight;
                }

                //获取文档完整的高度
                function getScrollHeight() {
                    return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
                }
            });
        })
    </script>
@endsection