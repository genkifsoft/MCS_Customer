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
        $std = new \StdClass;
        if(isset($dataJson['error_code']) && $dataJson['error_code'] !== 0)
        {
            $this->setVerifyCode($dataJson['error_code']);
            $this->setMessage('Update Failed');
            $this->setStatus(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $std->data = $dataJson['message'];
            $this->setBody($std);
        } else {
            $std->data = $dataJson['body'];

            $this->setBody($std);
        }
    }
}