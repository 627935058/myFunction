<?php

namespace Lijingping\SelfUse;

class Rsa
{
    //证书所在位置
    protected static $rsa_root_path;
    //公钥文件名称
    protected static string $public_key_name='public_key.pem';
    //私钥文件名称
    protected static string $private_key_name='private_key.pem';
    public function __construct($path='')
    {
        if(empty($path)){
            self::$rsa_root_path=dirname(__DIR__) . '/rsa/';
        }else{
            self::$rsa_root_path=$path;
        }
    }

    /**
     * 生成公钥秘钥
     * @return array
     *@author 云升网络
     * 2024/3/1 13:31
     */
    public function generateKeys(): array
    {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        file_put_contents(self::$rsa_root_path.self::$private_key_name,$privateKey);
        $publicKey = openssl_pkey_get_details($res);
        file_put_contents(self::$rsa_root_path.self::$public_key_name,$publicKey['key']);
        return [
            'private_key_name'=>self::$rsa_root_path.self::$private_key_name,
            'public_key_name'=>self::$rsa_root_path.self::$public_key_name,
            'public_key'=>$publicKey['key'],
            'private_key'=>$privateKey,
            'rsa_root_path'=>self::$rsa_root_path
        ];
    }
    /**
     * 公钥加密
     * @param $data
     * @param string $publicKey
     * @return string
     * @author 云升网络
     * 2024/3/1 11:58
     */
    public static function public_encrypt($data, string $publicKey=''): string
    {
        if(empty($publicKey)){
            $publicKey=file_get_contents(self::$rsa_root_path . self::$public_key_name);
        }
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return base64_encode($encrypted);
    }

    /**
     * 私钥解密
     * @param $data
     * @param string $privateKey
     * @return string
     * @author 云升网络
     * 2024/3/1 11:58
     */
    public static function private_decrypt($data, string $privateKey=''): string
    {
        if(empty($privateKey)){
            $privateKey=file_get_contents(self::$rsa_root_path . self::$private_key_name);
        }
        openssl_private_decrypt(base64_decode($data), $decrypted, $privateKey);
        return $decrypted??'';
    }

    /**
     * 私钥加密
     * @param $data
     * @param string $privateKey
     * @return string
     *@author 云升网络
     * 2024/4/10 17:56
     */
    public static function private_encrypt($data,string $privateKey=''): string
    {
        if(empty($privateKey)){
            $privateKey=file_get_contents(self::$rsa_root_path . self::$private_key_name);
        }
        openssl_private_encrypt($data,$encrypted,$privateKey);
        return base64_encode($encrypted);
    }

    /**
     * 公钥解密
     * @param $data
     * @param string $publicKey
     * @return string
     * @author 云升网络
     * 2024/4/10 17:56
     */
    public static function public_decrypt($data, string $publicKey=''): string
    {
        if(empty($publicKey)){
            $publicKey=file_get_contents(self::$rsa_root_path . self::$public_key_name);
        }
        openssl_public_decrypt(base64_decode($data),$decrypted,$publicKey);
        return $decrypted??'';
    }
}