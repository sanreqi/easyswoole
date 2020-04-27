<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2020/4/27
 * Time: 15:16
 */


if (!function_exists('test2')) {
    function test2() {
        print_r(567890);
    }
}

if (!function_exists('logic_exception')) {
    function logic_exception($code, $message = '') {
        throw new \App\Common\LogicException($code, $message);
    }
}