<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    //添加到购物车
    public function cartAdd()
    {
        $goods_id = request()->goods_id;
        $buy_number = request()->buy_number;
        //验证
        if (empty($goods_id)) {
            return ['code'=>5,'font'=>'请选择商品'];
        }
        if (empty($buy_number)) {
            return ['code'=>5,'font'=>'请选择购买数量'];
        }

        //判断是否登录
        //dd(checkLogin());
        if (checkLogin()) {
            return $this->saveCartDb($goods_id,$buy_number);
        }else{
            return ['code'=>5,'font'=>'请先登录'];
        }
    }

    //保存购物车数据到数据库中
    public function saveCartDb($goods_id,$buy_number)
    {
        $user_id = getUserId();
        //判断用户是否加入过购物车
        $where = [
            ['goods_id','=',$goods_id],
            ['user_id','=',$user_id],
            ['is_del','=',1]
        ];
        $cartInfo = DB::table('cart')->where($where)->first();
        if (!empty($cartInfo)) {
            //判断库存，累加
            $res = $this->checkGoodsNumber($goods_id,$buy_number,$cartInfo->buy_number);
            if ($res) {
                $updateInfo = [
                    'buy_number'=>$cartInfo->buy_number+$buy_number,
                ];
                $result = DB::table('cart')->where($where)->update($updateInfo);
                if ($result) {
                    return ['code'=>6,'font'=>'加入购物车成功'];
                }else{
                    return ['code'=>5,'font'=>'加入购物车失败'];
                }
            }else{
                return ['code'=>5,'font'=>'商品超过库存了'];
            }
        }else{
            //判断库存，新增
            $res = $this->checkGoodsNumber($goods_id,$buy_number);   //false true
            if ($res) {
                $info = ['goods_id'=>$goods_id,'buy_number'=>$buy_number,'user_id'=>$user_id];
                $result = DB::table('cart')->insert($info);
                if ($result) {
                    return ['code'=>6,'font'=>'加入购物车成功'];
                }else{
                    return ['code'=>5,'font'=>'加入购物车失败'];
                }
            }else{
                return ['code'=>5,'font'=>'商品超过库存了'];
            }
        }
    }

    //检测库存数量
    public function checkGoodsNumber($goods_id,$buy_number,$number = 0)
    {
        //根据商品id查询库存
        $goods_number = DB::table('goods')->where('goods_id',$goods_id)->value('goods_number');
        // echo $goods_number;exit;
        if ($buy_number + $number > $goods_number) {
            return false;
        }else{
            return true;
        }
    }

    //列表页
    public function cartList()
    {
        if (checkLogin()) {
            $user_id = getUserId();
            //从数据库中取购物车信息
            $cartInfo =  $this->getCartInfoDb();
//            dd($cartInfo);
            $countWhere = [
                'user_id'=>$user_id,
                'is_del'=>1
            ];
            $count = DB::table('cart')->where($countWhere)->count();
            if (!empty($cartInfo)) {
                foreach ($cartInfo as $k => $v) {
                    $total = $v->buy_number * $v->shop_price;
                    $v->total = $total;
                }
            }

            return view('cart.car',['cartInfo'=>$cartInfo,'count'=>$count]);
        }else{
            return redirect('/login');
        }
    }

    //从数据库中取购物车信息
    public function getCartInfoDb()
    {
        $user_id = getUserId();
        //查询购物车数据的时候。应该查询未删除的数据， 也就是is_del=1
        $where = [
            ['user_id','=',$user_id],
            ['is_on_sale','=',1],
            ['is_del','=',1]
        ];
        //双表联查
        $cartInfo = DB::table('cart')
                    ->join('goods','goods.goods_id','=','cart.goods_id')
                    ->where($where)
                    ->get();
        //dd($cartInfo);
        if (!empty($cartInfo)) {
            return $cartInfo;
        }else{
            return false;
        }

    }

    //获取商品总价
    public function countTotal()
    {
        $goods_id = request()->goods_id;
        $goods_id = explode(',',$goods_id);

        if (empty($goods_id)) {
            echo 0;exit;
        }
        if (checkLogin()) {
            $user_id = getUserId();
            $where = [
                ['is_on_sale','=',1],
                ['user_id','=',$user_id],
                ['is_del','=',1]
            ];
            $info = DB::table('cart')
                ->join('goods','goods.goods_id','=','cart.goods_id')
                ->where($where)
                ->whereIn('cart.goods_id',$goods_id)
                ->select('shop_price','buy_number')
                ->get();
            //dd($info);
            $count = 0;
            foreach ($info as $key => $value) {
                $count += $value->buy_number * $value->shop_price;  //$info对象 $value->shop_price
            }
            return $count;
        }else{
            return false;
        }

    }

    //更改购买数量
    public function changeBuyNumber()
    {
        $goods_id = request()->goods_id;
        $buy_number = request()->buy_number;

        if (empty($goods_id)) {
            return ['code'=>5,'font'=>'请至少选择一个商品'];
        }
        if (empty($buy_number)) {
            return ['code'=>5,'font'=>'购买数量不能为空'];
        }
        if (checkLogin()) {
            //检测库存
            $res = $this->checkGoodsNumber($goods_id,$buy_number);

            if ($res) {
                $where = [
                    'goods_id'=>$goods_id,
                    'user_id'=>getUserId()
                ];
                $updateInfo = [
                    'buy_number'=>$buy_number,
                    'update_time'=>time()
                ];

                $result = DB::table('cart')->where($where)->update($updateInfo);
                if ($result) {
                    return ['code'=>6,'font'=>'修改数量成功'];
                }else{
                    return ['code'=>5,'font'=>'修改数量失败'];
                }
            }else{
                return ['code'=>5,'font'=>'购买数量超过库存了'];
            }
        }else{
            return redirect('/login');
        }
    }

    //获取商品小计
    public function getSubTotal()
    {
        $goods_id = request()->goods_id;
        if (empty($goods_id)) {
            echo 0;exit;
        }

        if (checkLogin()) {
            //获取商品价格
            $goodsWhere = [
                ['is_on_sale','=',1],
                ['goods_id','=',$goods_id]
            ];
            $shop_price = DB::table('goods')->where($goodsWhere)->value('shop_price');

            //获取购买数量
            $user_id = getUserId();
            $cartWhere = [
                ['goods_id','=',$goods_id],
                ['user_id','=',$user_id]
            ];
            $buy_number = DB::table('cart')->where($cartWhere)->value('buy_number');
            $total = $shop_price*$buy_number;
            return $total;
        }else{
            return redirect('/login');
        }
    }

    //删除购物车数据
    public function cartDel()
    {
        $goods_id = request()->goods_id;
        $goods_id = explode(',',$goods_id);

        //判断是否登录
        if (checkLogin()) {
            //拼接条件
            $user_id = getUserId();
            $where = [
                ['user_id','=',$user_id]
            ];
            $updateInfo = ['is_del'=>2];  //
            $res = DB::table('cart')->where($where)->whereIn('goods_id',$goods_id)->update($updateInfo);
            if ($res) {
                return ['code'=>6,'font'=>'删除成功'];

            }else{
                return ['code'=>5,'font'=>'删除失败'];
            }
        }else{
            return redirect('/login');
        }
    }

    //判断是否登录
    public function isLogin()
    {
        $res = checkLogin();
        if ($res) {
            return ['code'=>6,'font'=>'已登陆'];
        }else{
            return ['code'=>5,'font'=>'请先登录'];

        }
    }

}
