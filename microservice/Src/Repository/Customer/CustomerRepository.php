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
        $this->data['status'] = "success";
        $data = [
            'id'            => customerId($this->count()),
            'email'         => $request->input('email'),
            'first_name'    => $request->input('first_name'),
            'last_name'     => $request->input('last_name'),
            'password'      => Hash::make($request->input('password')),
            'status'        => self::USER_ACTIVED,
        ];
        try {
            $this->data['body'] = $this->create($data);
        } catch(\Exception $e) {
            unset($this->data['status']);
            $this->data['error'] = "register_validation_error";
            $this->data['error_code'] = $e->errorInfo[1];
            $this->data['message'] =  $e->getMessage();
        }
        
        return $this;
    }

    public function getAllRepository($request)
    {
        $offset = $request->input('offset');
        $limit = $request->input('limit');
        $columns = ['id', 'email', 'first_name', 'last_name'];
        $this->data = $this->getAll($columns, $offset, $limit);

        return $this;
    }

    public function detailCustomerRepository($request)
    {
        $this->data['status'] = "success";
        try {
            $userId = JWTAuth::user()->id;
            $this->data['body'] = $this->find($userId);
            $this->data['body']->roles = $this->data['body']->roles == self::PERMISSION_USER ? "user" : ["user", "admin"];
        } catch(\Exception $e) {
            unset($this->data['status']);
            $this->data['error'] = "Unauthorized";
        }

        return $this;
    }

    public function loginRepository($request)
    {   
        $option = [
            'email' => $request->get('email'),
            'password' => urldecode($request->get('password')),
        ];
        $this->data['status'] = "success";     
        if (!$token = JWTAuth::attempt($option))
        {
            unset($this->data['status']);
            $this->data['error'] = "login_error";
            $this->data['error_code'] = JsonResponse::HTTP_NOT_FOUND;
            $this->data['message'] =  'Nguời dùng không tìm thấy';
        } else {
            $this->data['body']['token'] = $token;
        }

        return $this;
    }

    public function logoutRepository($request)
    {
        $token = $request->header('Authorization');

        try {
            JWTAuth::invalidate($token);
            $this->data['body'] = "User successfully logged out.";
        } catch (JWTException $e) {
            $this->data['error_code']  = 1;
            $this->data['message'] = "Failed to logout, please try again.";
        }

        return $this;
    }

    public function updateRepository($request)
    {
        $data = $request->only('last_name', 'first_name', 'address', 'phone');
        $columns = ['id', 'last_name', 'first_name', 'address', 'email', 'phone'];
        $this->data['error_code'] = 1;
        try {
            $checkPhoneExist = $this->checkPhoneBeforeUpdate(userId(), $request->get('phone'));
            if ($checkPhoneExist) {
                $this->data['message'] = 'Số điện thoại đã tồn tại';
                $this->data['error_code'] = 2;
            } else {
                $this->data['error_code'] = 0;
                $this->data['body'] = $this->update(userId(), $data, $columns);
            }
        } catch(\Exception $e) {
            $this->data['message'] =  $e->getMessage();
        }

        return $this;
    }

    public function deleteRepository($request)
    {
        try {
            $this->data['body'] = (boolean)$this->delete($request->get('id'));
            if ($this->data['body'] === false) {
                $this->data['body'] = JsonResponse::HTTP_NOT_FOUND;
            }
        } catch (\Exception $e) {
            $this->data['error_code'] = 1;
            $this->data['message'] =  $e->getMessage();
        }

        return $this;
    }

    public function refreshRepository($request)
    {
        try {
            $this->data['status'] = "success";
            $this->data['body'] = JWTAuth::refresh(JWTAuth::getToken());
        } catch(\JWTException $e) {
            unset($this->data['status']);
            $this->data['error'] = "refersh_token_error";
            $this->data['error_code'] = 1;
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
            $this->data['error_code']  = 1;
            $this->data['message'] ="Mật khẩu hiện tại không đúng";
        } else {
            $option['password'] = urldecode($request->input('password'));
            if (JWTAuth::attempt($option))
            {
                $this->data['error_code']  = 2;
                $this->data['message'] ="Mật khẩu mới không được đặt giống mật khẩu cũ";
            } else {
                try {
                    $option = [
                        'password' => Hash::make(urldecode($request->input('password'))),
                    ];
                    $this->data['body'] = $this->update($userId, $option, $columns = ['id']);
                } catch (JWTException $e) {
                    $this->data['error_code']  = 1;
                    $this->data['message'] = "Change password failed";
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
            $this->data['message'] = 'Người dùng không tồn tại';
            $this->data['error_code'] = 3;
        } else {
            $newPassword = generatePassword(8);
            $option = [
                'password' => Hash::make($newPassword),
            ];
            try {
                $this->data['body'] = $this->updatePassword($user->id, $option);
            } catch (\Exception $e) {
                $this->data['error_code']  = 1;
                $this->data['message'] = "Update pass failed";
            }
            if ((boolean)$this->data['body'] == true)
                $this->sendMailForgotPassword($this->data['body'], $request, $user, $newPassword);
        }

        return $this;
    }
}