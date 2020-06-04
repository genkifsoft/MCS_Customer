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

if (!function_exists('generatePassword')) {
    function generatePassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '0123456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if(!$add_dashes)
            return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;

        return $dash_str;
    }
}


if (!function_exists('mailer')) {
    function mailer($view, array $data, $callback, $queue = null) {
        if (config('mail.pretend')) {
            return;
        }
        // send / queue the email
        if (config('mail.should_queue')) {
            $queue = null == $queue ? config('queue.priority.low') : config('queue.priority.high');
            return Mail::queue($view, $data, $callback, $queue);
        } else {
            return Mail::send($view, $data, $callback);
        }
    }
}