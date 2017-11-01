<?php
if (!function_exists('dd')) {
    function dd(...$args) {
        echo '<pre>';
        foreach ($args as $arg) {
            echo "\n\n";
            var_export($arg);
        }
        echo '</pre>';
        exit();
    }
}

if (!function_exists('dp')) {
    function dp(...$args) {
        echo '<pre>';
        foreach ($args as $arg) {
            echo "\n\n";
            var_export($arg);
        }
        echo '</pre>';
    }
}

if (!function_exists('RouteGroup')) {
    function RouteGroup(...$args) {
        call_user_func_array([\Kernel\Route::class, 'group'], $args);
    }
}

if (!function_exists('Route')) {
    function Route(...$args) {
        call_user_func_array([\Kernel\Route::class, 'add'], $args);
    }
}