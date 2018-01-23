<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22
 * Time: 14:23
 */
namespace  Payment;
use Config\config;
use GuzzleHttp\Client;

class scanCodePayPayment
{
    private $config;
    private $paramSign;


    public function __construct($config)
    {
         $this->config = $config;
    }

    //
    public function stitchingParam($payParam){
        $accountId  = $this->config['accountId'];
        $payType    = $payParam['payType'];
        $commodity  = $payParam['commodity'];
        $amount     = $payParam['amount'];
        $responseUrl= $payParam['responseUrl'];
        $orderId    = $payParam['orderId'];
        $httpBuildQuery = [
            'accountId'=>$accountId,
            'orderId'=>$orderId,
            'payType'=>$payType,
            'commodity'=>$commodity,
            'amount'=>$amount,
            'responseUrl'=>$responseUrl,
        ];
        $bulidQueryStr =  http_build_query($httpBuildQuery);
        $mac        = $this->sign($bulidQueryStr);
        $httpBuildQuery['mac']  =$mac;

        $this->postRequest($httpBuildQuery);



    }



    public function sign($str){
        return strtoupper(md5($str));
    }

    public function postRequest($postData){
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => config::scanCodePayGateway,
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $respone  = $client->request('POST',config::scanCodePayGateway,[
                'form_params'=>$postData
        ]);
        $body  = $respone->getBody();
        print_r($body);


    }


}