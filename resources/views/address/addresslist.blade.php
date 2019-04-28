@extends('layouts.shop')
@section('title','微商城注册')
@section('content')
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>收货地址</h1>
        </div>
    </header>
    <div class="head-top">
        @include('public.header')
    </div><!--head-top/-->
    <table class="shoucangtab">
        <tr>
            <td width="75%"><a href="{{url('addressadd')}}" class="hui"><strong class="">+</strong> 新增收货地址</a></td>

        </tr>
    </table>

    <div class="dingdanlist">
        @foreach($addressInfo as $k=>$v)
            @if($v->is_default == 1)
                <table style="border:1px solid red;">
                    <tr>
                        <td width="50%">
                            <h3>{{$v->address_name}} {{$v->address_tel}}</h3>
                            <time>{{$v->address_detail}}</time>
                        </td>
                        <td align="right"><a href="{{url('address/addressedit/'.$v->address_id)}}" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
                        <td align="right"><a href="javsscript:;" class="hui del" address_id="{{$v->address_id}}"><span class="glyphicon glyphicon-check"></span> 删除信息</a></td>
                    </tr>
                </table>
             @else
                <table>
                    <tr>
                        <td width="50%">
                            <h3>{{$v->address_name}} {{$v->address_tel}}</h3>
                            <time>{{$v->address_detail}}</time>
                        </td>
                        <td align="right"><a href="{{url('address/addressedit/'.$v->address_id)}}" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
                        <td align="right"><a href="javsscript:;" class="hui del" address_id="{{$v->address_id}}"><span class="glyphicon glyphicon-check"></span> 删除信息</a></td>
                    </tr>
                </table>
            @endif
         @endforeach
    </div><!--dingdanlist/-->

    <div class="height1"></div>
    @include('public.footer')

    </div><!--maincont-->
    <script>
        $(function(){
            layui.use('layer',function(){
                var layer = layui.layer;
                var address_id = $('.del').attr('address_id');
                $('.del').click(function(){
                    layer.confirm('确认删除吗',function(){
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            method: "POST",
                            url: "/address/addressdel",
                            data: {address_id:address_id},
                        }).done(function( res ) {
                            layer.msg(res.font,{icon:res.code});
                            if (res.code == 6) {
                                //重新获取列表页面的数据
                                location.href="/address/addresslist";
                                //或者 把当前这一行元素删除
                            }
                        });
                    });
                });
            })
        })
    </script>
@endsection
