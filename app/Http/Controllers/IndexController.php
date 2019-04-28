<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //首页
    public function index()
    {
       $cateInfo= cache('cateInfo');
       // dd($cateInfo);die;
        //查询所有顶级分类名称
       if(!$cateInfo){
           $cateInfo = DB::table('category')->where('parent_id',0)->get();
           cache(['cateInfo'=>$cateInfo],60*24);
       }
        

        //轮播图数据
        $priceInfo = DB::table('goods')->orderBy('shop_price','desc')->limit(5)->select('goods_img','goods_id')->get();

        //显示商品信息
        $proInfo= cache('proInfo');
        //查询所有顶级分类名称
       if(!$proInfo){
            $proInfo = DB::table('goods')->orderBy('goods_number','desc')->limit(6)->get();
           cache(['proInfo'=>$proInfo],60*24);
       }
       

    	return view('index.index',['proInfo'=>$proInfo,'cateInfo'=>$cateInfo,'priceInfo'=>$priceInfo]);
    }


}
