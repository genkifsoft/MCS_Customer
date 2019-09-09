<?php

namespace MicroService\Src\Repository\Customer;

use Hash;
use JWTAuth;
use Illuminate\Http\JsonResponse;

class CustomerRepository extends CustomerEloquentRepository
{
    const USER_ACTIVED = 1;
    public $data = [];

    public function createRepository($params_request)
    {
        $this->data['status'] = "success";
        $data = [
            'id'            => customerId($this->count()),
            'email'         => $params_request['email'],
            'password'      => Hash::make($params_request['password']),
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

    public function getAllRepository($params_request)
    {
        $offset = $params_request['offset'];
        $limit = $params_request['limit'];
        $columns = ['id', 'email', 'first_name', 'last_name'];
        
        $this->data = $this->getAll($columns, $offset, $limit);

        return $this;
    }

    public function detailCustomerRepository($params_request)
    {
        $this->data['status'] = "success";
        try {
            $userId = JWTAuth::user()->id;
            $columns = ['id', 'email', 'first_name', 'last_name'];
            $this->data['body'] = $this->find($userId, $columns);
        } catch(\Exception $e) {
            unset($this->data['status']);
            $this->data['error'] = "Unauthorized";
        }

        return $this;
    }

    public function loginRepository($params_request)
    {   
        $params_request['password'] = urldecode($params_request['password']);
        $this->data['status'] = "success";     
        $option = array_only($params_request, ['email', 'password']);
        
        if (!$token = JWTAuth::attempt($option))
        {
            unset($this->data['status']);
            $this->data['error'] = "login_error";

            $this->data['error_code'] = JsonResponse::HTTP_NOT_FOUND;
            $this->data['message'] =  'Nguời dùng không tìm thấy';
        } else {
            $this->data['body'] = 'Bearer '. $token;
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
        $data = [
            'last_name'    => $request->get('last_name'),
            'first_name'   => $request->get('first_name'),
            'address'      => $request->get('address'),
            'phone'        => $request->get('phone'),
        ];
        $columns = ['id', 'last_name', 'first_name', 'address', 'email', 'phone'];
        try {
            $checkPhoneExist = $this->checkPhoneBeforeUpdate(userId(), $request->get('phone'));
            if ($checkPhoneExist) {
                $this->data['body'] = 'Số điện thoại đã tồn tại';
            } else {
                $this->data['body'] = $this->update(userId(), $data, $columns);
            }
        } catch(\Exception $e) {
            $this->data['error_code'] = 1;
            $this->data['message'] =  $e->getMessage();
        }

        return $this;
    }

    public function deleteRepository($params_request)
    {
        try {
            $this->data['body'] = (boolean)$this->delete($params_request['id']);
            if ($this->data['body'] === false) {
                $this->data['body'] = JsonResponse::HTTP_NOT_FOUND;
            }
        } catch(\Exception $e) {
            $this->data['error_code'] = 1;
            $this->data['message'] =  $e->getMessage();
        }

        return $this;
    }
}