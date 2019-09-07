<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use MicroService\Src\Entity\Json\GetEntity;
use MicroService\Src\Entity\Json\CreateEntity;
use MicroService\Src\Entity\Json\DeleteEntity;
use MicroService\Src\Entity\Json\UpdateEntity;
use MicroService\Src\Repository\Customer\CustomerRepository;
use App\Http\Requests\Customer\LoginRequest;

class CustomerController
{
    protected $params_request;
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->params_request  = request()->all();
        unset($this->params_request['params_request']);
    }

    public function createCustomer(Request $request)
    {
        $data = $this->customerRepository->createRepository($this->params_request);
        $create_json = new CreateEntity;
        $create_json->setParamByResponse($data);
        $result = $create_json->toJson();

        return $result;
    }

    public function getAllCustomer(Request $request)
    {
        $data = $this->customerRepository->getAllRepository($this->params_request);
        $get_json = new GetEntity($data);
        $result   = $get_json->toJson();

        return $result;
    }

     /**
     * Only allow login is email
     * Not use phone login
     */
    public function loginCustomer(LoginRequest $request)
    {
        $data = $this->customerRepository->loginRepository($this->params_request);
        $create_json = new CreateEntity;
        $create_json->setParamByResponse($data);
        $result   = $create_json->toJson();

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
        $create_json = new UpdateEntity;
        $create_json->setParamByResponse($data);
        $result = $create_json->toJson();

        return $result;
    }

    public function deleteCustomer(Request $request)
    {
        $data = $this->customerRepository->deleteRepository($this->params_request);
        $create_json = new DeleteEntity;
        $create_json->setParamByResponse($data);
        $result = $create_json->toJson();

        return $result;
    }
}