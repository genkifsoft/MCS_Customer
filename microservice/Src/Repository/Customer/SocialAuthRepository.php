<?php

namespace MicroService\Src\Repository\Customers;

use Hash;
use Cache;
use Illuminate\Http\JsonResponse;

class SocialAuthRepository
{
    use \MicroService\Src\Traits\Singleton;

    public function callbackSocialRepository($user, $typeSocial)
    {
        $url      = config('customer.login_faceboook');
        dd($user);
        // $params   = $request->only('email', 'password');
        $jsonData = CustomerRepository::post($url, $params);

        return $jsonData;
    }
}