<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\OrderDetail;

class OrderController extends Controller
{
    //订单页
    public function confirmPay()
    {
        $goods_id = request()->goods_id;
        $goods_id = explode(',',$goods_id);

        if (empty($goods_id)) {
            return redirect('cartlist');
        }

        //获取购物车数据
        $info = $this->getCartInfo($goods_id);
        //查询收货地址
        $addressInfo = $this->getAddressInfo();
        //dd($addressInfo);

        return view('order.confirmpay',['info'=>$info,'addressInfo'=>$addressInfo]);
    }

    //获取购物车数据
    public function getCartInfo($goods_id)
    {
        $user_id = getUserId();
        $where = [
            ['user_id','=',$user_id],
            ['is_del','=',1],
            ['is_on_sale','=',1]
        ];
        $cartData = DB::table('cart')
            ->join('goods','goods.goods_id','=','cart.goods_id')
            ->where($where)
            ->whereIn('cart.goods_id',$goods_id)
            ->select('shop_price','buy_number','goods_name','goods_img','goods.goods_id')
            ->get();
        $cartData = json_decode(json_encode($cartData),true);

        $count = 0;
        foreach ($cartData as $key => $value) {
            $cartData[$key]['subTotal'] = $subTotal = $value['shop_price']*$value['buy_number'];
            $count += $subTotal;
        }
        $info = [];
        $info['cartData'] = $cartData;
        $info['count'] = $count;
        return $info;

    }

    //查询收货信息
    public function getAddressInfo()
    {
        $user_id = getUserId();
        if(empty($user_id)){
            return ['code'=>5,'font'=>'请先登录'];
            return redirect('/login');
        }
        $where = [
            ['user_id','=',$user_id],
            ['is_del','=',1]
        ];
        //查询出的是对象格式，即使是空对象，empty也检测不出来，认为是有值，所以toArray()转化为数组
        $addressInfo = DB::table('address')->where($where)->get()->toArray();


        if (!empty($addressInfo)) {

            foreach ($addressInfo as $key => $value) {
                $value->province = DB::table('area')->where('id', $value->province)->value('name');
                $value->city = DB::table('area')->where('id', $value->city)->value('name');
                $value->area = DB::table('area')->where('id', $value->area)->value('name');
            }
            //dd($addressInfo);
        }
            return $addressInfo;
//            dd($addressInfo);
//        }else{
//            return false;
//        }
    }

    //提交订单
    public function submitOrder()
    {
        //获取数据
        $goods_id = request()->goods_id;
        $address_id = request()->address_id;

        //验证
        if (empty($goods_id)) {
            return ['code'=>5,'font'=>'请至少选择一个商品'];
        }
        if (empty($address_id)) {
            return ['code'=>5,'font'=>'必须选择收货地址'];
        }

        //异常处理
        try{
            $user_id = getUserId();  //获取用户id

            //开启事务
            DB::beginTransaction();

            //添加订单数据到order表
            $order_no = $this->createOrderNo(); //生成订单号
            $order_amount = $this->getOrderAmount($goods_id);  //获取订单总金额

            $orderInfo['user_id'] = $user_id;
            $orderInfo['order_no'] = $order_no;
            $orderInfo['order_amount'] = $order_amount;
            $order_id = DB::table('order')->insertGetId($orderInfo);

            // $res1 = false;
            if (empty($order_id)) {
                //手动抛出错误
                throw new \Exception('订单信息添加失败');
            }

            //订单详情表添加
            $goodsInfo = $this->getOrderDetail($goods_id);

            foreach ($goodsInfo as $key => $value) {
                $value->order_id = $order_id;
                $value->user_id = $user_id;
                $goodsInfo[$key] = (array)$value;
            }
            //dd($goodsInfo);  //二维数组
            if (empty($goodsInfo)) {
                throw new \Exception('没有商品详情数据');
            }
            foreach ($goodsInfo as $k=>$v) {
                $res2 = OrderDetail::create($v);
                //dd($res2);
                if (empty($res2)) {
                    throw new \Exception('订单详情写入失败');
                }
            }


            //订单收货地址
            $addressWhere = [
                'address_id'=>$address_id,
                'is_del'=>1
            ];
            $addressInfo = DB::table('address')->where($addressWhere)->first();
            //dd($addressInfo);
            if (empty($addressInfo)) {
                throw new \Exception('没有收货地址，请重新选择');
            }
            $addressInfo = (array)$addressInfo;
            unset($addressInfo['create_time']);
            unset($addressInfo['update_time']);
            unset($addressInfo['address_mail']);
            unset($addressInfo['address_id']);
            unset($addressInfo['is_default']);
            $addressInfo['order_id'] = $order_id;
            //dd($addressInfo);
            $res3 = DB::table('order_address')->insert($addressInfo);
            if (!$res3) {
                throw new \Exception('订单收货地址添加失败');
            }

            //删除购物车数据
            $cartWhere = [
                ['user_id','=',$user_id],
                ['is_del','=',1]
            ];
            $res4 = DB::table('cart')->where($cartWhere)->whereIn('goods_id',explode(',',$goods_id))->update(['is_del'=>2]);
            if (empty($res4)) {
                throw new \Exception('删除购物车失败');
            }

            //减少库存
            foreach ($goodsInfo as $key => $value) {
                $goodsWhere = [
                    ['goods_id','=',$value['goods_id']]
                ];
                $updateInfo = [
                    'goods_number' => $value['goods_number']-$value['buy_number']
                ];
                $res5 = DB::table('goods')->where($goodsWhere)->update($updateInfo);

                if (empty($res5)) {
                    throw new \Exception('库存修改失败');
                }
            }

            DB::commit();
            $arr = [
                'code'=>6,
                'font'=>'下单成功',
                'order_id'=>$order_id
            ];
            return $arr;
        }catch(\Exception $e){
            DB::rollBack();
            return ['font'=>$e->getMessage(),'code'=>5];
        }
    }

    //生成订单号
    public function createOrderNo()
    {
        //年月日8位+随机数4位+用户id
        return date('Ymd').rand(1000,9999).getUserId();
    }

    //订单总金额
    public function getOrderAmount($goods_id)
    {
        $user_id = getUserId();
        $goods_id = explode(',',$goods_id);
        //双表联产获取购物车表中的购买数量和商品表中的商品价格
        $where = [
            ['user_id','=',$user_id],
            ['is_del','=',1],
            ['is_on_sale','=',1]
        ];
        $cartInfo = DB::table('cart')
            ->join('goods','goods.goods_id','=','cart.goods_id')
            ->where($where)
            ->whereIn('cart.goods_id',$goods_id)
            ->select('shop_price','buy_number')
            ->get();
        $count = 0;
        foreach ($cartInfo as $key => $value) {
            $count += $value->shop_price * $value->buy_number;
        }
        // echo $count;
        return $count;

    }

    //获取商品详情信息
    public function getOrderDetail($goods_id)
    {
        $user_id = getUserId();

        $goods_id = explode(',',$goods_id);
        $where = [
            ['user_id','=',$user_id],
            ['is_on_sale','=',1],
            ['is_del','=',1]
        ];
        $goodsInfo = DB::table('cart')
            ->join('goods','goods.goods_id','=','cart.goods_id')
            ->where($where)
            ->whereIn('cart.goods_id',$goods_id)
            ->select('goods.goods_id','goods_name','goods_img','goods_number','shop_price','buy_number')
            ->get()
            ->toArray();
        return $goodsInfo;
    }

    //订单列表页
    public function orderList()
    {
        $user_id = getUserId();
        //查询订单
        $orderid = DB::table('order')->where('user_id',$user_id)->select('order_id')->get()->toArray();
        dd($orderid);

        return view('order.orderlist');
    }

    //订单提交成功页面
    public function success()
    {
        $order_id = request()->order_id;

        //查询订单信息
        $orderInfo = DB::table('order')->where('order_id',$order_id)->first();
        //dd($orderInfo);
        return view('order.success',['orderInfo'=>$orderInfo]);
    }
}
