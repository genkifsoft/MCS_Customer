<?php

/*
|--------------------------------------------------------------------------
| Common global system helper functions
|--------------------------------------------------------------------------
|
| This file contains system helper functions that might be used in this
| application. Some helpers could be short-handed by easy-to-remember
| functions instead of calling long class names and methods.
|
 */
if (!function_exists('time_now')) {
    function time_now() {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}

if (!function_exists('createSubId')) {
    function createSubId($length = 30) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('customerId'))
{
    function customerId($count)
    {
        $string = createSubId(3);
        $idCus  = $string. str_pad(++$count, 5, '0', STR_PAD_LEFT)
                .substr(strtotime(time_now()), -3, 3);

        return $idCus;
    }
}

if (!function_exists('userId'))
{
    function userId()
    {
        return JWTAuth::user()->id;
    }
}