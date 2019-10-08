<?php

namespace MicroService\Src\Traits;

use JWTAuth;

trait JWT
{
    public static function auth()
    {
        return JWTAuth::user();
    }

    public static function authID()
    {
        return JWTAuth::user()->id;
    }

    public static function authFullName()
    {
        return JWTAuth::user()->first_name .' '. JWTAuth::user()->last_name;
    }

    public static function authPhone()
    {
        return JWTAuth::user()->phone;
    }

    public static function authAddress()
    {
        return JWTAuth::user()->address;
    }

    public static function authPhotoId()
    {
        return JWTAuth::user()->photo_id;
    }

    public static function authEmail()
    {
        return JWTAuth::user()->email;
    }
}