<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2022/7/20
 * Time: 15:29
 */

namespace ShanDe;


class Client
{
    public $key;
    public $mer_no;
    public $mer_key;
    public $sign_type;
    public $product_code;

    protected $data;

    public function __construct($mer_no,$mer_key,$key,$product_code,$sign_type="MD5")
    {
        $this->key = $key;
        $this->mer_no = $mer_no;
        $this->mer_key = $mer_key;
        $this->sign_type = $sign_type;
        $this->product_code = $product_code;
    }
    protected function filter($data){
        $signData = [
            'version' => 10,
            'mer_no' =>  $this->mer_no, //商户号
            'mer_key' => $this->mer_key, // 商户私钥通过安卓APK工具解析出来的KEY1
            'mer_order_no' => $data['mer_order_no']??null, //商户唯一订单号
            'create_time' => date('YmdHis'),
            'order_amt' => $data['order_amt']??null, //订单支付金额
            'notify_url' => $data['notify_url']??null, //订单支付异步通知
            'return_url' => $data['return_url']??null, //订单前端页面跳转地址
            'create_ip' => $data['create_ip']??null,
            'store_id' => $data['store_id']??null,
            'pay_extra' => $data['pay_extra']??null,
            'accsplit_flag' => $data['accsplit_flag']??'No',
            'sign_type' => $this->sign_type,
            'activity_no' => $data['activity_no']??null,
            'benefit_amount' => $data['benefit_amount']??null,
            'extend' => $data['extend']??null,
            'merch_extend_params ' => $data['merch_extend_params ']??null
        ];
        $this->data = $signData;
        return $signData;
    }
    public function sign($data){
        $data = $this->filter($data);
        $sign = $this->getSignContent($data).'&key='.$this->key;
        $sign = md5($sign);
        return strtoupper($sign);
    }
    public function param($data){
        $this->data['sign'] = $this->sign($data);
        $this->data['product_code'] = $this->product_code;
        return array_merge($this->data,$data);
    }
    protected function getSignContent($params) {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
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
    protected function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }
}