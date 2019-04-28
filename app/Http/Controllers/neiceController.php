8<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class neiceController extends Controller
{
    public function index()
    {
    	$query = request()->all();
    	$where = [];
    	$goods_name = $query['goods_name']??'';
    	if($goods_name){
    		$where[]=['goods_name','like',"%$goods_name%"];
    	}
    	$data = DB::table('goods')->where($where)->paginate(3);
    	// dd($data);die;
    	return view('/neice/index',compact('data','goods_name','query'));
    }

    public function info()
    {
       $id=request()->goods_id;
    	// dd($id);
       $goodsInfo = cache('goodsInfo'.$id);
       // dd($goodsInfo);
       if(!$goodsInfo){
       	  $goodsInfo = DB::table('goods')->where('goods_id',$id)->first();
       	  // dd($data);
       	  cache(['goodsInfo_'.$id=>$goodsInfo],60*24);
       	  // dd($goodsInfo);die;
       }
    	return view('/neice/info',['goodsInfo'=>$goodsInfo]);
    }

    public function delete()
    {
    	$goods_id = request()->goods_id;
    	// dd($goods_id);
    	$data = DB::table("goods")->where('goods_id',$goods_id)->delete();
    	cache(['goodsInfo'=>''],0);
    	// dd($data);
    	if($data == 1){
            return 1;
    	}else{
    	    return 2;
    	}
    }

    public function edit()
    {
    	$id=request()->id;
    	// dd($id);
    	$data = DB::table('goods')->where('goods_id',$id)->first();
    	// dd($data);
    	return view('/neice/edit',['data'=>$data]);
    }

    public function update(Request $request)
    {

    	$post = request()->except('_token');
    	// dd($post['goods_img']);
    	$id= $post['goods_id'];
    	$goods_img= $post['goods_img'];
    	// dd($goods_img);
    	$goodsInfo=cache('goodsInfo'.$id);
    	// dd($goodsInfo);
    	if(!$goodsInfo){
    		 if(!$request->hasfile('goods_img')){
             	$this->upload($request,$goods_img);
              dd($post['goods_img']);
              // unset($post['edit_goods_img']);
            }
        // dd($post);die;
        $res = DB::table('goods')
                 ->where('goods_id', $id)
                 ->update($post);
                 // dd($res);die;
                 cache(['goodsInfo_'.$id=>$res],60*24); 



		if($res !== false){
			return 1;
		}else{
			return 2;
		}


    	}
    	// dd($res);
    	
       
    }

    public function upload($request,$name)
    {
    	// dd($name);
			 if ( $request->file($name)->isValid()) {
				 $photo = $request->file($name);
				 dd($photo);
				 $extension = $photo->extension();
				 //$store_result = $photo->store('photo');
				 $store_result = $photo->storeAs(date('Ymd'), date('YmdHis').rand(1000,9999).'.'.$extension);
				
				 return  $store_result;
			}
			exit('文件上传出现错误！');
					
	}
}
?>