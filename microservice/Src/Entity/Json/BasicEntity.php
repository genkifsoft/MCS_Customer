<?php

namespace MicroService\Src\Entity\Json;

use Illuminate\Http\JsonResponse;
use MicroService\Src\Interfaces\interfaceResponse;

class BasicEntity implements interfaceResponse
{
    protected $header;
    protected $body = [];
    protected $status = JsonResponse::HTTP_OK;
    public $api_name;

    public function __construct()
    {
        $this->setStatus($this->status);
        $this->setVerifyCode(0);
        $this->setMessage(null);
        $this->setBody([]);
        
        if(request()->route())
        {
            $this->body['api_name'] = request()->route()->getActionMethod();
        } else {
            $this->body['api_name'] = 'Api not found!';
        }
    }

    public function setMessage($message)
    {
        $this->body['header']['message'] = $message;
    }

    public function setVerifyCode($result)
    {
        $this->body['header']['error_code'] = $result;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->body['header']['status_code'] = $status;
    }

    public function setBody($body)
    {
        $this->body['body'] = $body;
    }

    public function setError($message)
    {
        $this->body['error'] = $message;
    }

    public function setSuccess($message)
    {
        $this->body['status'] = $message;
    }

    /**
     * responseJson. be call from controller
     * @return json
     */
    function toJson(){
        return response()->json($this->body, $this->status);
    }

    function toJsonHeader($token){
        return response()->json($this->body, $this->status)
                        ->header('Content-Type', 'application/json')
                        ->header('Authorization', 'Bearer '.$token['token']);
    }
}