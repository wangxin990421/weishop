@extends('layouts.shop')
@section('title','微商城首页')
@section('content')
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>我的订单</h1>
        </div>
    </header>
    <div class="head-top">
        @include('public.header')
    </div><!--head-top/-->

    <div class="zhaieq oredereq">
        <a href="javascript:;" class="zhaiCur">待付款</a>
        <a href="javascript:;">待发货</a>
        <a href="javascript:;">已取消</a>
        <a href="javascript:;" style="background:none;">已完成</a>
        <div class="clearfix"></div>
    </div><!--oredereq/-->

    <div class="dingdanlist" onClick="window.location.href='proinfo.html'">
        <table>
            <tr>
                <td colspan="2" width="65%">订单号：<strong>PO20150819111145</strong></td>
                <td width="35%" align="right"><div class="qingqu"><a href="javascript:;" class="orange">订单取消</a></div></td>
            </tr>
            <tr>
                <td class="dingimg" width="15%"><img src="images/pro1.jpg" /></td>
                <td width="50%">
                    <h3>三级分销农庄有机瓢瓜400g</h3>
                    <time>下单时间：2015-08-11  13:51</time>
                </td>
                <td align="right"><img src="images/jian-new.png" /></td>
            </tr>
            <tr>
                <th colspan="3"><strong class="orange">¥36.60</strong></th>
            </tr>
        </table>
    </div><!--dingdanlist/-->

    <div class="dingdanlist" onClick="window.location.href='proinfo.html'">
        <table>
            <tr>
                <td colspan="2" width="65%">订单号：<strong>PO20150819111145</strong></td>
                <td width="35%" align="right"><div class="qingqu"><a href="javascript:;" class="orange">订单取消</a></div></td>
            </tr>
            <tr>
                <td class="dingimg" width="15%"><img src="images/pro1.jpg" /></td>
                <td width="50%">
                    <h3>三级分销农庄有机瓢瓜400g</h3>
                    <time>下单时间：2015-08-11  13:51</time>
                </td>
                <td align="right"><img src="images/jian-new.png" /></td>
            </tr>
            <tr>
                <th colspan="3"><strong class="orange">¥36.60</strong></th>
            </tr>
        </table>
    </div><!--dingdanlist/-->

    <div class="height1"></div>
    @include('public.footer')
@endsection