<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use MicroService\Src\Entity\Json\GetEntity;
use MicroService\Src\Entity\Json\CreateEntity;
use MicroService\Src\Entity\Json\DeleteEntity;
use MicroService\Src\Entity\Json\UpdateEntity;
use MicroService\Src\Repository\Customer\CustomerRepository;
use App\Http\Requests\Customer\LoginCustomerRequest;
use App\Http\Requests\Customer\CreateCustomerRequest;
use App\Http\Requests\Customer\UpdatePasswordRequest;

class CustomerController
{
    protected $params_request;
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function createCustomer(CreateCustomerRequest $request)
    {
        $data = $this->customerRepository->createRepository($request);
        $create_json = new CreateEntity;
        $create_json->setParamByResponse($data);
        $result = $create_json->toJson();

        return $result;
    }

    public function getAllCustomer(Request $request)
    {
        $data = $this->customerRepository->getAllRepository($request);
        $get_json = new GetEntity($data);
        $result   = $get_json->toJson();

        return $result;
    }

    public function detailCustomer(Request $request)
    {
        $data = $this->customerRepository->detailCustomerRepository($request);
        $get_json = new GetEntity($data);
        $result   = $get_json->toJson();

        return $result;
    }
    
     /**
     * Only allow login is email
     * Not use phone login
     */
    public function loginCustomer(LoginCustomerRequest $request)
    {
        $data = $this->customerRepository->loginRepository($request);
        $create_json = new CreateEntity;
        $create_json->setParamByResponse($data);

        $result = $create_json->toJsonHeader($data->data['token']);

        return $result;
    }

    public function logoutCustomer(Request $request)
    {
        $data = $this->customerRepository->logoutRepository($request);
        $get_json = new GetEntity($data);
        $result   = $get_json->toJson();

        return $result;
    }

    public function updateCustomer(Request $request)
    {
        $data = $this->customerRepository->updateRepository($request);
        $update_json = new UpdateEntity;
        $update_json->setParamByResponse($data);
        $result = $update_json->toJson();

        return $result;
    }

    public function deleteCustomer(Request $request)
    {
        $data = $this->customerRepository->deleteRepository($request);
        $delete_json = new DeleteEntity;
        $delete_json->setParamByResponse($data);
        $result = $delete_json->toJson();

        return $result;
    }

    public function refreshCustomer(Request $request)
    {
        $data = $this->customerRepository->refreshRepository($request);
        $get_json = new GetEntity($data);
        $token = $data->data['token'];
        $result   = $get_json->toJsonHeader($token);

        return $result;
    }

    public function changePassword(UpdatePasswordRequest $request)
    {
        $data = $this->customerRepository->changePassword($request);
        $update_json = new UpdateEntity;
        $update_json->setParamByResponse($data);
        $result = $update_json->toJson();

        return $result;
    }

    public function fogotPassword(Request $request)
    {
        $data = $this->customerRepository->fogotPassword($request);
        $update_json = new UpdateEntity;
        $update_json->setParamByResponse($data);
        $result = $update_json->toJson();

        return $result;
    }
}