<?php

namespace MicroService\Src\Entity\Json;

use Illuminate\Http\JsonResponse;

class DeleteEntity extends BasicEntity
{
    public function __construct()
    {
        parent::__construct();
        $this->setVerifyCode(0);
        $this->setMessage('Delete Success');
        $this->setStatus(JsonResponse::HTTP_OK);
    }

    public function setParamByResponse($response)
    {
        $dataJson = (array)$response->data;
        if(isset($dataJson['error_code']) && $dataJson['error_code'] !== 0)
        {
            $this->setVerifyCode($dataJson['error_code']);
            $this->setMessage('Delete Failed');
            $this->setStatus(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $this->setBody($dataJson['message']);
        } else if ($dataJson['body'] === 404) {
            $this->setMessage('Delete Failed');
            $this->setVerifyCode(1);
            $this->setStatus(JsonResponse::HTTP_NOT_FOUND);
            $this->setBody(false);
        } else {
            $std = new \StdClass;
            $std->data = $dataJson['body'];

            $this->setBody($std);
        }
    }
}