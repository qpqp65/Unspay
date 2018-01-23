<?php
require_once  '../vendor/autoload.php';
$config  = require_once 'payconfig.php';


$delegatePay =  new \Payment\delegatePay($config);
$delegatePay->stitchingParam([
    'name'=>'张成',
    'cardNo'=>'6228480068681093370',
    'amount'=>0.01,
    'responseUrl'=>'http://www.boma298.com/',
    'purpose'=>'学费',
    'orderId'=>time()

]);
$a  =$delegatePay->run();
if($a === false){
    echo  $delegatePay->getError();
}


