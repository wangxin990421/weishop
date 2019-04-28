@extends('layouts.shop')
@section('title','微商城首页')
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
    <form action="" method="" class="reg-login">
        <div class="lrBox">
            <div class="lrList"><input type="text" placeholder="收货人" id="address_name"/></div>
            <div class="lrList"><input type="text" placeholder="详细地址" id="address_detail"/></div>
            <div class="lrList">
                <select id="province" class="changearea">
                    <option value="" selected="selected">省份/直辖市</option>
                    @foreach($provinceInfo as $k=>$v)
                    <option value="{{$v->id}}">{{$v->name}}</option>
                    @endforeach
                </select>
            {{--</div>--}}
            {{--<div class="lrList">--}}
                <select id="city" class="changearea">
                    <option value="" selected="selected">区县</option>
                    <option value=""></option>
                </select>
            {{--</div>--}}
            {{--<div class="lrList">--}}
                <select id="area" class="changearea">
                    <option value="" selected="selected">详细地址</option>
                    <option value=""></option>
                </select>
            </div>
            <div class="lrList"><input type="text" placeholder="手机" id="address_tel"/></div>
            <div class=""><input type="checkbox" id="is_default">设为默认收货地址</div>
        </div><!--lrBox/-->
        <div class="lrSub">
            <input type="button" id="add" value="保存" />
        </div>
    </form><!--reg-login/-->

    @include('public.footer')
    <script>
        $(function() {
            layui.use('layer',function() {
                var layer = layui.layer;
                //内容改变
                $(".changearea").change(function(){
                    var _this = $(this);
                    var _option = "<option value='0' selected='selected'>--请选择--</option>";
                    _this.nextAll('select').html(_option);
                    var id = _this.val();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post(
                        "/address/getarea",
                        {id:id},
                        function(res) {
                            for(var i in res){
                                _option += "<option value='"+res[i]['id']+"'>"+res[i]['name']+"</option>";
                            }
                            // console.log(res);
                            _this.next('select').html(_option);
                        },
                    );
                });

                //点击添加
                $('#add').click(function(){
                    var obj = {};
                    obj.province = $('#province').val();
                    obj.city = $('#city').val();
                    obj.area = $('#area').val();
                    obj.address_name = $('#address_name').val();
                    obj.address_tel = $('#address_tel').val();
                    obj.address_name = $('#address_name').val();
                    obj.address_detail = $('#address_detail').val();
                    var is_default = $("#is_default").prop('checked');
                    var tel = /^1(3|4|5|7|8)\d{9}$/;
                    if(obj.address_name==''){
                        layer.msg('收货人姓名必填',{icon:2});
                        return false;
                    }
                    if(obj.address_detail==''){
                        layer.msg('详细地址必填',{icon:2});
                        return false;
                    }
                    //验证
                    if(obj.province==''){
                        layer.msg('省份必填',{icon:2});
                        return false;
                    }
                    if(obj.city==''){
                        layer.msg('城市必填',{icon:2});
                        return false;
                    }
                    if(obj.area==''){
                        layer.msg('城市必填',{icon:2});
                        return false;
                    }

                    if(obj.address_tel==''){
                        layer.msg('电话必填',{icon:2});
                        return false;
                    }else if(!tel.test(obj.address_tel)){
                        layer.msg('手机号格式不正确',{icon:5})
                        return false;
                    }
                    if (is_default == 1) {
                        obj.is_default = 1;
                    }else{
                        obj.is_default = 2;
                    }

                    //添加
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method: "POST",
                        url: "/address/addressadddo",
                        data: {obj},
                    }).done(function( res ) {
                        layer.msg(res.font,{icon:res.code},function(){
                            if(res.code==6){
                                location.href="/address/addresslist";
                            }else{
                                location.href="/addressadd";
                            }
                        });
                    });

                });


            })
        })

    </script>
@endsection