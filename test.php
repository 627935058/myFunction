<?php
/**
 * @author 云升网络
 * 2025/1/3 17:03
 **/
require 'vendor/autoload.php';
use Lijingping\SelfUse\Aes;
use Lijingping\SelfUse\IdCard;
use Lijingping\SelfUse\Rsa;
use Lijingping\SelfUse\Timer;
use Lijingping\SelfUse\Sundry;
use Lijingping\SelfUse\Distance;

/**
 * aes 的使用
 */
$en_data=Aes::encrypt('hello');
var_dump($en_data);
$de_data=(new Aes())->decrypt($en_data);
var_dump($de_data);
/**
 * rsa的使用
 */
////创建rsa公钥私钥(创建时需要先实例化传入rsa公钥秘钥存储位置文件夹【绝对路径】)
//$path='';//公钥私钥存储位置，为空则存贮在src文件夹同级的rsa文件夹下
//$rsa=new Rsa($path);
//$create_keys=$rsa->generateKeys();
//var_dump($create_keys);
//var_dump('创建的公钥私钥将会存储在【'.$create_keys['rsa_root_path'].'】文件夹下');
//在使用时
////如果使用的是默认存储位置
//$rsa_en_data=Rsa::private_encrypt('hello');
////或
//(new Rsa())->private_encrypt('hello');
////都可以
//
////如果公钥秘钥的存储位置非默认位置或者您已有自己的公钥私钥
//// $path='您公钥私钥所在文件夹的绝对路径';
//$rsa_en_data=Rsa::private_encrypt('hello',$path.'/private_key.pem');
////或
//(new Rsa($path))->private_encrypt('hello');
////也可以

/**
 * Timer的使用
 */
var_dump(Timer::now());
/**
 * IdCard的使用
 */
var_dump(IdCard::getInfo('10101010101010101011'));
/**
 * Sundry的使用
 */
var_dump(Sundry::array_dfs([1,2,3]));
/**
 * Distance的使用（单位为米）
 */
$pos =  [116.377904, 39.915423];
$path = [[116.368904, 39.913423],[116.382122, 39.901176],[116.387271, 39.912501],[116.398258, 39.904600]];
var_dump(Distance::point_to_line($pos, $path).'米');