<?php

require '../config.php';
require ROOT . '/bootstrap.php';

session_start();

(new Kernel\Http())->dispatch();