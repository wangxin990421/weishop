<?php
/**
 * 获取楼层分类下的所有子类的id
 */
function getCateId($cateInfo,$parent_id)
{
    //静态属性
    static $id = [];
    foreach($cateInfo as $key=>$value){
        if ($value->parent_id == $parent_id) {
            $id[] = $value->cate_id;
            getCateId($cateInfo,$value->cate_id);
        }
    }
    return $id;
}

//检测是否登录
function checkLogin()
{
    return \Auth::user();   //没值返回null
}

//获取登录用户id
function getUserId()
{
    return \Auth::user()->id;
}











?>