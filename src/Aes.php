<?php
namespace Lijingping\SelfUse;
/**
 * @author 云升网络
 * 2025/1/3 17:00
 **/
class Aes
{
    protected static $key='9dNnGWz73e14cQF1';
    protected static $iv='3zyJFPEzh5rUeUNi';

    /**
     * AES加密
     * @param String $data  需要加密的数据
     * @param String $key   秘钥
     *  @param String $model 操作模式
     * @return string
     * @author 云升网络
     * 2025/1/3 17:01
     */
    public static function encrypt(string $data, string $key='', string $model='cbc'): ?string
    {
        if(empty($key)){
            $key = md5(static::$key);
        }
        if($model=='ecb'){
            return self::encrypt_ecb($data, $key);
        }elseif($model=='cbc'){
            return self::encrypt_cbc($data, $key);
        }
        return null;
    }

    /**
     * AES解密
     * @param String $data 需要解密的数据
     * @param String $key 秘钥
     * @param String $model 操作模式
     * @return string
     */
    public static function decrypt(string $data, string $key='', string $model='cbc'): ?string
    {
        if(empty($key)){
            $key = md5(static::$key);
        }
        if($model=='ecb'){
            return self::decrypt_ecb($data, $key);
        }elseif($model=='cbc'){
            return self::decrypt_cbc($data, $key);
        }
        return null;
    }

    private static function encrypt_ecb($data, $key): string
    {
        return base64_encode(openssl_encrypt($data, 'AES-128-ECB', $key, OPENSSL_RAW_DATA));
    }
    private static function decrypt_ecb($data, $key)
    {
        return openssl_decrypt(base64_decode($data), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    }

    private static function encrypt_cbc($data, $key) {
        return openssl_encrypt($data, 'AES-128-CBC', $key, 0, self::$iv);
    }

    private static function decrypt_cbc($data, $key) {
        return openssl_decrypt($data, 'AES-128-CBC', $key, 0, self::$iv);
    }
}