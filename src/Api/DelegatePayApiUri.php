<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22
 * Time: 16:27
 */

namespace Api;


use Config\config;

class DelegatePayApiUri
{
    protected  $payUri  = config::baseDelegatePayGateway.'delegatePay/pay';

}