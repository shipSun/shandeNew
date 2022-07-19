<?php
/**
 * Created by PhpStorm.
 * User: ship
 * Date: 2022/7/19
 * Time: 16:00
 */
namespace ShanDe;

class Sign{
    public static function encrypt($data,$key,$type){
        $sign = static::getSignContent($data).'&key='.$key;
        if($type == 'md5'){
            $sign = md5($sign);
        }
        return strtoupper($sign);
    }
    protected static function getSignContent($params) {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === static::checkEmpty($v) && "@" != substr($v, 0, 1)) {

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }
    protected static function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }
    public static function vertify($data, $key, $sign, $type){
        if(static::encrypt($data, $key, $type)==$sign){
            return true;
        }
        return false;
    }
}