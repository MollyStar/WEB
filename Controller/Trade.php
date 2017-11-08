<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/11/8
 * Time: 下午7:08
 */

namespace Controller;

use Common\AlipayHelper;
use Kernel\Request;
use Kernel\Response;

class Trade
{
    public function initiate() {
        $gateway = AlipayHelper::AopGateway();
        $request = $gateway->purchase();
        $request->setBizContent([
            'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
            'total_amount' => 0.01,
            'subject'      => 'test',
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ]);

        /**
         * @var AopCompletePurchaseResponse $response
         */
        $response = $request->send();

        $redirectUrl = $response->getRedirectUrl();
        //or
        Response::redirect($redirectUrl);
        // $response->redirect();
    }


    public function notify() {
        $params = array_merge($_POST, $_GET);
        if (empty($params)) {
            return;
        }
        $request = AlipayHelper::AopGateway()->completePurchase();
        $request->setParams($params); //Don't use $_REQUEST for may contain $_COOKIE

        /**
         * @var AopCompletePurchaseResponse $response
         */
        try {
            $response = $request->send();

            if ($response->isPaid()) {
                /**
                 * Payment is successful
                 */
                die('success'); //The notify response should be 'success' only
            } else {
                /**
                 * Payment is not successful
                 */
                die('fail'); //The notify response
            }
        } catch (Exception $e) {
            /**
             * Payment is not successful
             */
            die('fail'); //The notify response
        }
    }


    public function return () {
        $params = array_merge($_POST, $_GET);
        if (empty($params)) {
            return;
        }
        $request = AlipayHelper::AopGateway()->completePurchase();
        $request->setParams($params); //Don't use $_REQUEST for may contain $_COOKIE

        /**
         * @var AopCompletePurchaseResponse $response
         */
        try {
            $response = $request->send();

            if ($response->isPaid()) {
                /**
                 * Payment is successful
                 */
                die('success'); //The notify response should be 'success' only
            } else {
                /**
                 * Payment is not successful
                 */
                die('fail'); //The notify response
            }
        } catch (Exception $e) {
            /**
             * Payment is not successful
             */
            die('fail'); //The notify response
        }
    }
}