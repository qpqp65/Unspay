<?php
/**
 * 代付接口类
 *
 */

namespace Payment;


use Api\DelegatePayApiUri;
use GuzzleHttp\Client;

class delegatePay extends DelegatePayApiUri
{
    private $config;
    private $error;
    private $param;
    public function getError(){
        return $this->error;
    }
    public function __construct($config)
    {
         $this->config  = $config;
    }

    public function stitchingParam($payParam){
        $accountId  = $this->config['accountId'];
        $name    = $payParam['name'];//姓名
        $cardNo  = $payParam['cardNo'];//卡号
        $amount     = $payParam['amount'];//付款金额
        $responseUrl= $payParam['responseUrl'];//回调地址
        $orderId    = $payParam['orderId'];//商家订单号
        $purpose    = $payParam['purpose'];//付款目的
        $httpBuildQuery = [
            'accountId'=>$accountId,
            'name'=>$name,
            'cardNo'=>$cardNo,
            'orderId'=>$orderId,
            'purpose'=>$purpose,
            'amount'=>$amount,
            'responseUrl'=>$responseUrl,
            'key'=>$this->config['key']
        ];
         $bulidQueryStr =  http_build_query($httpBuildQuery);
         $mac        = $this->sign(urldecode($bulidQueryStr));

        $httpBuildQuery['mac']  = $mac;
        $this->param  = $httpBuildQuery;





    }
    public function run(){

        $response =  $this->postRequest($this->param);
         if($response['result_code']==='0000'){
            return true;
        }else{

             $this->error  =$response['result_msg'];
            return false;
        }
    }




    public function sign($str){
        return strtoupper(md5($str));
    }


    public function postRequest($postData){
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->payUri,
            // You can set any number of default request options.
            'timeout'  => 10.0,
        ]);
        $respone  = $client->request('POST',$this->payUri,[
            'form_params'=>$postData
        ]);

        $body  = $respone->getBody()->getContents();
        $body  =  json_decode($body,true);
        return $body;


    }






}