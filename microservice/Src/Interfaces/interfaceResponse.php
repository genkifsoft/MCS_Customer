<?php

namespace MicroService\Src\Interfaces;

interface interfaceResponse
{
    function setMessage($message);

    function setVerifyCode($result);

    function setStatus($status);

    function setBody($body);

    public function toJson();
}