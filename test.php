<?php
/**
 * @author 云升网络
 * 2025/1/3 17:03
 **/
require 'vendor/autoload.php';
use Lijingping\SelfUse\Aes;
use Lijingping\SelfUse\Rsa;
use Lijingping\SelfUse\Timer;

//$res=(new Rsa())::generateKeys();
$start = '2025-01-06 15:00:00';
var_dump(Timer::getAfterDaysArr($start,7));
//var_dump($res);
//var_dump(dirname(__DIR__));