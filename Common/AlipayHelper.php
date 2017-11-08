<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/8
 * Time: 下午7:39
 */

namespace Common;


use Kernel\Config;
use Kernel\Request;
use Omnipay\Omnipay;

class AlipayHelper
{
    private static $GET_WAY = [];

    public static function AopGateway() {

        if (!isset(self::$GET_WAY[__FUNCTION__])) {
            $config = Config::get('alipay');
            $gateway = Omnipay::create('Alipay_AopPage');
            $gateway->setSignType($config['sign_type']); //RSA/RSA2
            $gateway->setAppId($config['app_id']);
            $gateway->setPrivateKey(file_get_contents($config['private_key_file']));
            $gateway->setAlipayPublicKey(file_get_contents($config['public_key_file']));
            $gateway->setReturnUrl(Request::basePath() . '/' . $config['return_url']);
            $gateway->setNotifyUrl(Request::basePath() . '/' . $config['notify_url']);
            self::$GET_WAY[__FUNCTION__] = $gateway;
        }

        return self::$GET_WAY[__FUNCTION__];
    }
}