<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/17
 * Time: 1:12
 */

namespace Controller;

use Gregwar\Captcha\CaptchaBuilder;

class Common
{
    public function verifiation() {
        $builder = new CaptchaBuilder;
        $builder->build();

        $_SESSION['phrase'] = $builder->getPhrase();

        header('Content-type: image/jpeg');
        $builder->output();
    }
}