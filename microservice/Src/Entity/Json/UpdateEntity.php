<?php

namespace MicroService\Src\Entity\Json;

use Illuminate\Http\JsonResponse;

class UpdateEntity extends BasicEntity
{
    const NOT_CHANGE_DATA = 0;

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
        if(isset($dataJson['error_code']))
        {
            if ($dataJson['error_code'] != 0)
            {
                $this->setVerifyCode($dataJson['error_code']);
                $this->setMessage('Update Failed');
                $std->data = $dataJson['message'];
                $this->setBody($std);

                switch ($dataJson['error_code'])
                {
                    case 1:
                        $this->setStatus(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
                    break;

                    case 2:
                        $this->setStatus(JsonResponse::HTTP_CONFLICT);
                    break;

                    case 3:
                        $this->setStatus(JsonResponse::HTTP_NOT_FOUND);
                    break;

                    default:
                        # code...
                    break;
                }
            }
        } else {
            if ((int)$dataJson['body'] === self::NOT_CHANGE_DATA)
            {
                /**
                 * update not change data
                 */
                $this->setStatus(JsonResponse::HTTP_RESET_CONTENT);
                $std->data = 'Dữ liệu không thay đổi';
            } else {
                $std->data = $dataJson['body'];
                $this->setBody($std);
            }
        }
    }
}