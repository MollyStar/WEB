<?php
/**
 * Created by IntelliJ IDEA.
 * User: shinate
 * Date: 2017/10/16
 * Time: 23:01
 */

$r->addRoute('GET', '/register', ['Controller\Register', 'index']);
$r->addRoute('POST', '/register/save', ['Controller\Register', 'save']);
$r->addRoute('GET', '/verifiation.jpg', ['Controller\Common', 'verifiation']);