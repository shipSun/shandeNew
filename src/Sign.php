<?php
/**
 * Created by PhpStorm.
 * User: ship
 * Date: 2022/7/19
 * Time: 16:00
 */
namespace ShanDe;

class Sign{
    public static function encrypt($data,$type){

    }

    public static function vertify($data, $sign, $type){
        if(static::encrypt($data,$type)==$sign){
            return true;
        }
        return false;
    }
}