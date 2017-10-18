<?php
if (!function_exists('dd')) {
    function dd($value = null) {
        echo '<pre>';
        var_export($value);
        echo '</pre>';
        exit();
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