<?php

namespace MicroService\Src\Entity\Json;

use Illuminate\Http\JsonResponse;

class GetEntity extends BasicEntity
{
    public function __construct($data)
    {
        parent::__construct();

        $std = new \stdClass;
        $this->setVerifyCode(0);
        $this->setMessage('Get Success');
        $this->setStatus(JsonResponse::HTTP_OK);

        if(!empty((array) $data)){
            $this->setBody($data);
            if (isset($data->data['status']))
            {
                $std->data = $data->data['body'];
                $std->status = "success";
                $this->setBody($std);
            }
        } else {
            $this->setVerifyCode(1);
            $this->setMessage('Get Failed');
            $this->setStatus(JsonResponse::HTTP_NO_CONTENT);
            
            if (isset($data['error']))
            {
                $this->status = 401;
                $this->setError($data->$data['error']);
            }
        }
    }

    /**
     * HTTP_NOT_ACCEPTABLE is not accept.
     * Param requried incorect.
     * 
     * Code 195: Missing or invalid url parameter
     */
    public function setHeaderMiddlewareResponse()
    {
        $this->setStatus(JsonResponse::HTTP_NOT_ACCEPTABLE);
        $this->setVerifyCode(195);
        $this->setMessage('secret key wrong !');
    }
}