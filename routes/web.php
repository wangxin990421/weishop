<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
//首页
Route::get('/','IndexController@index');
Route::any('login','LoginController@login');  //登录
Route::any('reg','LoginController@reg');      //注册
Route::post('reg/checktel','LoginController@checkEmailTel');   //验证唯一性
//Route::post('reg/checkemail','LoginController@checkEmail');
Route::post('reg/send','LoginController@send');     //发送验证码
Route::any('prolist/{cate_id?}','ProductController@prolist');   //商品列表
//Route::any('prolist/goodsinfo','ProductController@getGoodsInfo');   //商品列表页条件展示

Route::any('proinfo/{goods_id?}','ProductController@proinfo');    //商品详情页

Route::any('addcart','CartController@cartAdd');   //加入购物车
Route::any('cartlist','CartController@cartList')->middleware('islogin');    //购物车列表页
Route::any('counttotal','CartController@countTotal');    //获取总价
Route::any('changenum','CartController@changeBuyNumber');   //更改数据库购买数量
Route::any('getsubtotal','CartController@getSubTotal');   //更改商品小计
Route::any('delcart','CartController@cartDel');   //删除购物车数据
Route::any('/cart/islogin','CartController@isLogin');   //判断是否登录

Route::any('confirmpay/{goods_id}','OrderController@confirmPay');   //确认订单页面
Route::any('orderlist','OrderController@orderList')->middleware('islogin');   //订单列表页
Route::any('addressadd','AddressController@addressAdd');    //添加收货地址页面

Route::any('address/getarea','AddressController@getArea');   //获取地区
Route::any('address/addressadddo','AddressController@addressAddDo');   //添加收货地址
Route::any('address/addresslist','AddressController@addressList');   //收货地址列表页
Route::any('address/addressedit/{address_id}','AddressController@addressEdit');   //修改收货地址页
Route::any('address/addresseditdo','AddressController@addressEditHandle');   //修改收货地址页
Route::any('address/addressdel','AddressController@addressDel');  //删除收货地址
Route::any('address/submitorder','OrderController@submitOrder');  //删除收货地址

Route::any('success/{order_id}','OrderController@success');   //订单提交成功页面
Route::any('pay/{order_id}','PayController@aliPay');   //支付宝页面
Route::get('returnAlipay',"PayController@returnAlipay");  //支付宝同步回调页面
Route::post('notefiyUrl',"PayController@notefiyUrl"); 
Route::prefix('alipay')->group(function(){
    Route::get('mobilepay',"AliPayController@mobilepay");
    Route::any('return',"AliPayController@re");
    Route::any('notify',"AliPayController@notify");
});
//Route::any('paysuccess',function(){
//    echo "<script>alert('支付成功');location.href='/'</script>";
//});

Route::prefix('user')->middleware('islogin')->group(function(){
    Route::any('index','UserController@user');   //用户视图页
    Route::any('logout','LoginController@logout');   //用户视图页
});



Route::get('test','LoginController@test');

//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
Route::any('/neice/index','neiceController@index');
Route::any('/neice/info/{goods_id}','neiceController@info');
Route::any('/neice/delete','neiceController@delete');
Route::any('/neice/edit/{id}','neiceController@edit');
Route::any('/neice/update','neiceController@update');