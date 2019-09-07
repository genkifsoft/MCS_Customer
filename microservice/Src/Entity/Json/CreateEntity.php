<?php

namespace MicroService\Src\Entity\Json;

use Illuminate\Http\JsonResponse;

class CreateEntity extends BasicEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setVerifyCode(0);
        $this->setMessage('Create Success');
        $this->setStatus(JsonResponse::HTTP_CREATED);
    }

    /**
     * Set params infor error
     * Error 500 is error server
     * JsonResponse::HTTP_INTERNAL_SERVER_ERROR: 500
     */
    public function setParamByResponse($response)
    {
        $dataJson = (array)$response->data;
        if(isset($dataJson['error_code']) && $dataJson['error_code'] !== 0)
        {
            $this->setVerifyCode($dataJson['error_code']);
            $this->setMessage('Create Failed');
            $this->setStatus(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $this->setBody($dataJson['message']);
            if (isset($dataJson['error']))
                $this->setError($dataJson['error']);
        } else {
            if (isset($dataJson['status']))
                $this->setSuccess($dataJson['status']);

            $std = new \StdClass;
            $std->data = $dataJson['body'];
            $this->setBody($std);
        }
    }
}