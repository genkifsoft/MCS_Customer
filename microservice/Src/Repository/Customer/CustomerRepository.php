<?php

namespace MicroService\Src\Repository\Customer;

use Hash;
use JWTAuth;
use Illuminate\Http\JsonResponse;

class CustomerRepository extends CustomerEloquentRepository
{
    const USER_ACTIVED = 1;
    const PERMISSION_ADMIN = 2;
    const PERMISSION_USER = 1;

    public $data = [];

    use \MicroService\Src\Traits\JWT;
    use \MicroService\Src\Traits\Singleton;

    public function createRepository($request)
    {
        $data = [
            'id'            => customerId($this->count()),
            'email'         => $request->input('email'),
            'first_name'    => $request->input('first_name'),
            'last_name'     => $request->input('last_name'),
            'password'      => Hash::make($request->input('password')),
            'status'        => self::USER_ACTIVED,
        ];
        try {
            $this->data = $this->create($data);
        } catch(\Exception $e) {
            $this->data['message'] = $e->getMessage();
            $this->data['status_response'] =  JsonResponse::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS;
        }
        
        return $this;
    }

    public function getAllRepository($request)
    {
        $limit = $request->get('limit', LIMIT_PAGE);
        $this->data = $this->paginate($limit);

        return $this;
    }

    public function detailCustomerRepository($request)
    {
        try {
            $userId = JWTAuth::user()->id;
            $this->data = $this->find($userId);
        } catch(\Exception $e) {
            $this->data['message'] = $e->getMessage();
            $this->data['status_response'] = JsonResponse::HTTP_NOT_FOUND;
        }

        return $this;
    }

    public function loginRepository($request)
    {   
        $option = [
            'email' => $request->get('email'),
            'password' => urldecode($request->get('password')),
        ];
        if (!$token = JWTAuth::attempt($option))
        {
            $this->data['status_response'] = JsonResponse::HTTP_NOT_FOUND;
            $this->data['message'] =  'Nguời dùng không tìm thấy';
        } else {
            $this->data['token'] = $token;
        }

        return $this;
    }

    public function logoutRepository($request)
    {
        $token = $request->header('Authorization');

        try {
            JWTAuth::invalidate($token);
            $this->data = "User successfully logged out.";
        } catch (JWTException $e) {
            $this->data['status_response'] = JsonResponse::UNAUTHORIZED;
            $this->data['message'] = "Failed to logout, please try again.";
        }

        return $this;
    }

    public function updateRepository($request)
    {
        $data = $request->only('last_name', 'first_name', 'address', 'phone');
        try {
            $checkPhoneExist = $this->checkPhoneBeforeUpdate(userId(), $request->get('phone'));
            if ($checkPhoneExist) {
                $this->data['message'] = 'Customer_Exists_451';
                $this->data['status_response'] =  JsonResponse::HTTP_CONFLICT;
            } else {
                $this->data = $this->update(['id' => userId()], $data);
            }
        } catch(\Exception $e) {
            $this->data['message'] =  $e->getMessage();
            $this->data['status_response'] =  JsonResponse::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS;
        }

        return $this;
    }

    public function deleteRepository($request)
    {
        try {
            $this->data = $this->delete($request->get('id'));
        } catch (\Exception $e) {
            $this->data['status_response'] = JsonResponse::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS;
            $this->data['message'] =  $e->getMessage();
        }

        return $this;
    }

    public function refreshRepository($request)
    {
        try {
            $this->data['token'] = JWTAuth::refresh(JWTAuth::getToken());
        } catch(\JWTException $e) {
            $this->data['status_response'] = $e->getStatusCode();
            $this->data['message'] =  $e->getMessage();
        }

        return $this;
    }

    public function changePassword($request)
    {
        $newInstance = CustomerRepository::getInstance();
        $userId = $newInstance::authID();
        $email = $newInstance::authEmail();
        $option = [
            'password' => urldecode($request->input('current_pass')),
            'email' => $email,
        ];
        if (!JWTAuth::attempt($option))
        {
            $this->data['status_response']  = JsonResponse::HTTP_UNAUTHORIZED;
            $this->data['message'] = "Update_Password_401";
        } else {
            $option['password'] = urldecode($request->input('password'));
            if (JWTAuth::attempt($option))
            {
                $this->data['status_response']  = JsonResponse::HTTP_CONFLICT;
                $this->data['message'] = "Update_Password_409";
            } else {
                try {
                    $option = [
                        'password' => Hash::make(urldecode($request->input('password'))),
                    ];
                    $this->data = $this->update(['id' => $userId], $option);
                } catch (JWTException $e) {
                    $this->data['status_response']  = JsonResponse::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS;
                    $this->data['message'] = $e->getMessage();
                }
            }
        }

        return $this;
    }

    public function fogotPassword($request)
    {
        $user = $this->findEmail($request->get('email'));
        if (empty($user))
        {
            $this->data['message'] = 'Forgot_Password_404';
            $this->data['status_response'] = JsonResponse::HTTP_NOT_FOUND;
        } else {
            $newPassword = generatePassword(8);
            $option = [
                'password' => Hash::make($newPassword),
            ];
            try {
                $this->data = $this->updatePassword($user->id, $option);
            } catch (\Exception $e) {
                $this->data['status_response']  = JsonResponse::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS;;
                $this->data['message'] = $e->getMessage();
            }
            
            if ((boolean)$this->data === true)
                $this->sendMailForgotPassword($this->data, $request, $user, $newPassword);
        }

        return $this;
    }
}