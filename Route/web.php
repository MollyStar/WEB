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

$r->addRoute('GET', '/drop', ['Controller\Server', 'drop']);
$r->addRoute('GET', '/drop/import', ['Controller\Server', 'drop_import']);
$r->addRoute('GET', '/drop/clean', ['Controller\Server', 'drop_clean']);

$r->addRoute('GET', '/mob', ['Controller\Server', 'mob']);
$r->addRoute('GET', '/mob/import', ['Controller\Server', 'mob_import']);
$r->addRoute('POST', '/mob/update', ['Controller\Server', 'mob_update']);