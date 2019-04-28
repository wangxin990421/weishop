<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //商品列表页
    public function prolist()
    {
        if(request()->ajax() && request()->isMethod('post')){
            $cate_id = request()->cate_id;
            $searchdata = request()->searchdata;

            $field = request()->field;
            $type = request()->type;
            $floor = request()->floor;
            $floor = ($floor-1)*4;
            $where = [
                ['is_on_sale','=',1]
            ];

//            if ($is_type == 1){
//                $where = [
//                    ['is_new','=',1]
//                ];
//                $field = 'goods_id';
//                $type = 'desc';
//            }else if($is_type == 2){
//                //库存
//                $field = 'goods_number';
//                $type = 'desc';
//            }else if($is_type == 3){
//                $field = 'shop_price';
//                $type = 'asc';
//            }

            if(!empty($searchdata)){
                $where = [
                    ['goods_name','like',"%$searchdata%"]
                ];
            }

            if(!empty($cate_id)){
                $cateInfo = DB::table('category')->get();
                $c_id = getCateId($cateInfo,$cate_id);
                //$goodsInfo = DB::table('goods')->where($where)->whereIn('cate_id',$c_id)->orderBy($field,$type)->limit(10)->get();
                $goodsInfo = DB::table('goods')->where($where)->whereIn('cate_id',$c_id)->orderBy($field,$type)->skip($floor)->take(10)->get();

            }else{
                //$goodsInfo = DB::table('goods')->where($where)->orderBy($field,$type)->limit(10)->get();
                $goodsInfo = DB::table('goods')->where($where)->orderBy($field,$type)->skip($floor)->take(10)->get();
            }
            if (!empty($goodsInfo)){
                return view('goods.showinfo',['goodsInfo'=>$goodsInfo]);
            }else{
                return 0;
            }
            //dd($goodsInfo);

        }else{
            //首次加载
            //获取商品加分页
            $cate_id = request()->cate_id;
            $searchname = request()->searchname;
            $where = [
                ['is_on_sale','=',1]
            ];
            if(!empty($searchname)){
                $where = [
                    ['goods_name','like',"%$searchname%"]
                ];
            }
            if(!empty($cate_id)){
                $cateInfo = DB::table('category')->get();
                $c_id = getCateId($cateInfo,$cate_id);
                //$paginate = config('app.pageSize',6);
                $goodsInfo = DB::table('goods')->where($where)->whereIn('cate_id',$c_id)->limit(10)->get();
            }else{
                //$paginate = config('app.pageSize',6);
                $goodsInfo = DB::table('goods')->where($where)->limit(10)->get();
            }
            return view('goods.prolist',['goodsInfo'=>$goodsInfo,'cate_id'=>$cate_id,'searchname'=>$searchname]);
        }

    }


    //商品详情页
    public function proinfo()
    {
        //获取商品信息
        $goods_id = request()->goods_id;
        //dd($goods_id);
        if (!empty($goods_id)){
            $goodsInfo = DB::table('goods')->where('goods_id',$goods_id)->first();
            return view('goods.proinfo',['goodsInfo'=>$goodsInfo]);
        }else{
            // echo 2;exit;
            return redirect('/');
        }
    }
}
