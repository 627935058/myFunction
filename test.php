<?php
/**
 * @author 云升网络
 * 2025/1/3 17:03
 **/
require 'vendor/autoload.php';
use Lijingping\SelfUse\Aes;
use Lijingping\SelfUse\Rsa;
use Lijingping\SelfUse\Timer;
use Lijingping\SelfUse\Sundry;

var_dump(Timer::getDayArr('2025-01-01 23:00:00', '2025-01-9 23:00:00'));