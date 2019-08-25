<?php

namespace MicroService\Src\Entity\Json;

use Illuminate\Http\JsonResponse;

class UpdateEntity extends BasicEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setVerifyCode(0);
        $this->setMessage('Update Success');
        $this->setStatus(JsonResponse::HTTP_OK);
    }

    public function setParamByResponse($response)
    {
        $dataJson = (array)$response->data;
        if(isset($dataJson['error_code']) && $dataJson['error_code'] !== 0)
        {
            $this->setVerifyCode($dataJson['error_code']);
            $this->setMessage('Update Failed');
            $this->setStatus(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $this->setBody($dataJson['message']);
        } else {
            $std = new \StdClass;
            $std->data = $dataJson['body'];

            $this->setBody($std);
        }
    }
}