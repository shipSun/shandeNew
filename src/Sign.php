<?php
/**
 * Created by PhpStorm.
 * User: ship
 * Date: 2022/7/19
 * Time: 16:00
 */
namespace ShanDe;

class Sign{
    public static function filter($data){
        $signData = [
            'version' => $data['version']??10,
            'mer_no' =>  $data['mer_no']??null, //商户号
            'mer_key' => $data['mer_key']??null, // 商户私钥通过安卓APK工具解析出来的KEY1
            'mer_order_no' => $data['mer_order_no']??null, //商户唯一订单号
            'create_time' => date('YmdHis'),
            'order_amt' => $data['order_amt']??null, //订单支付金额
            'notify_url' => $data['notify_url']??null, //订单支付异步通知
            'return_url' => $data['return_url']??null, //订单前端页面跳转地址
            'create_ip' => $data['create_ip']??null,
            'store_id' => $data['store_id']??null,
            'pay_extra' => $data['pay_extra']??null,
            'accsplit_flag' => $data['accsplit_flag']??'No',
            'sign_type' => $data['sign_type']??'MD5',
            'activity_no' => $data['activity_no']??null,
            'benefit_amount' => $data['benefit_amount']??null,
            'extend' => $data['extend']??null,
            'merch_extend_params ' => $data['merch_extend_params ']??null
        ];
        return $signData;
    }
    public static function encrypt($data,$key,$type){
        $data = static::filter($data);
        $sign = static::getSignContent($data).'&key='.$key;
        if($type == 'MD5'){
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