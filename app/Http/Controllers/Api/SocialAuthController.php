<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SocialAccountService;
use MicroService\Src\Repository\Customers\SocialAuthRepository;
use Socialite;
use Laravel\Socialite\Two\User;

class SocialAuthController extends Controller
{
    private $_socialAuthRepository;

    public function __construct(SocialAuthRepository $_socialAuthRepository)
    {
        $this->_socialAuthRepository = $_socialAuthRepository;
    }

    public function redirect($social)
    {
        return Socialite::driver($social)->redirect();
    }

    public function callback($social)
    {
        $dataCallback = Socialite::driver($social)->user();
        $this->_socialAuthRepository->callbackSocialRepository($dataCallback, $social);
    }
}