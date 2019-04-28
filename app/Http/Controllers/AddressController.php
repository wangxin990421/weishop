<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    //添加收货地址页面
    public function addressAdd()
    {
        //查询省份信息pid == 0
        $provinceInfo = $this->getAreaInfo(0);
        return view('address.addressadd',['provinceInfo'=>$provinceInfo]);
    }

    //获取区域
    public function getArea()
    {
        $id = request()->id;
        if (empty($id)) {
            return ['code'=>'5','font'=>'请选择省份'];
        }
        return $areaInfo = $this->getAreaInfo($id);  //二维数组

    }

    //获取地区
    public function getAreaInfo($pid)
    {
        $where = [
            ['pid','=',$pid]
        ];
        $areaInfo = DB::table('area')->where($where)->get();
//        dd($areaInfo);
//        $areaInfo = json_decode(json_encode($areaInfo));
//        dd($areaInfo);
        if (!empty($areaInfo)) {
            return $areaInfo;
        }else{
            return false;
        }
    }

    //执行添加收货地址
    public function addressAddDo()
    {
        $data = request()->post();
        //dd($data);   //二维数组形式
        //dd($data['obj']['is_default']);
        if ($data['obj']['is_default']==1) {
            $user_id = getUserId();
            DB::table('address')->where('user_id',$user_id)->update(['is_default'=>2]);
        }
        $data['obj']['user_id'] = $user_id;
        $res = DB::table('address')->insert($data['obj']);
        if ($res) {
            return ['code'=>'6','font'=>'添加成功'];
        }else{
            return ['code'=>'5','font'=>'添加失败'];
        }
    }

    //收货地址列表页
    public function addressList()
    {
        //查询收货地址列表
        $user_id = getUserId();
        $where = [
            ['user_id','=',$user_id],
            ['is_del','=',1]
        ];
        //查询出的是对象格式，即使是空对象，empty也检测不出来，认为是有值，所以toArray()转化为数组
        $addressInfo = DB::table('address')->where($where)->get();
        if (!empty($addressInfo)) {
            foreach ($addressInfo as $key => $value) {
                $value->province=DB::table('area')->where('id',$value->province)->value('name');
                $value->city=DB::table('area')->where('id',$value->city)->value('name');
               $value->area=DB::table('area')->where('id',$value->area)->value('name');
            }
        }else{
            return false;
        }
        return view('address.addresslist',['addressInfo'=>$addressInfo]);
    }

    //修改收货地址
    public function addressEdit()
    {
        $address_id = request()->address_id;
        $user_id = getUserId();
        if (empty($address_id)) {
            return redirect('address/addresslist');
        }

        //根据用户id查询出修改的一条数据作为默认值
        $where = [
            ['address_id','=',$address_id],
            ['is_del','=',1]
        ];
        $addressInfo = DB::table('address')->where($where)->first();

        //查询省份信息
        $provinceInfo = $this->getAreaInfo(0);

        //获取市信息
        $cityInfo = $this->getAreaInfo($addressInfo->province);

        //获取区信息
        $areaInfo = $this->getAreaInfo($addressInfo->city);

        return view('address.addressedit',compact('addressInfo','provinceInfo','cityInfo','areaInfo'));
    }

    //订单修改执行
    public function addressEditHandle()
    {
        $data = request()->post();

        if ($data['obj']['is_default']==1) {
            $user_id = getUserId();
            $where = [
                ['user_id','=',$user_id]
            ];
            //开启事务
            DB::beginTransaction();
            $res1 = DB::table('address')->where($where)->update(['is_default'=>2]);   //不会报错，返回值受影响的行数
            $res2 = DB::table('address')->where('address_id',$data['obj']['address_id'])->update($data['obj']);
            if ($res1 !== false && $res2 !== false) {
                DB::commit();
                return ['code'=>6,'font'=>'修改成功'];
            } else {
                DB::rollBack();
                return ['code'=>6,'font'=>'修改失败'];
            }

        }else{
            $res = DB::table('address')->where('address_id',$data['obj']['address_id'])->update($data['obj']);
            if ($res !== false) {
                return ['code'=>6,'font'=>'修改成功'];
            } else {
                return ['code'=>6,'font'=>'修改失败'];
            }

        }
    }

    //删除收货地址
    public function addressDel()
    {
        $address_id = request()->address_id;
        $where = [
            ['address_id','=',$address_id]
        ];
        $res = DB::table('address')->where($where)->update(['is_del'=>2]);
        if($res){
            return ['code'=>6,'font'=>'删除成功'];
        }else{
            return ['code'=>5,'font'=>'修改失败'];
        }
    }
}
