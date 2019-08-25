<?php

namespace MicroService\Src\Repository\Customer;

use App\Models\Customer;
use MicroService\Src\Repository\EloquentRepository;

class CustomerEloquentRepository extends EloquentRepository
{
     /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Customer::class;
    }

    protected function createCustomerId()
    {
        $string = createSubId(3);
        $count  = $this->_model->count();
        $idCus  = $string. str_pad(++$count, 5, '0', STR_PAD_LEFT)
                .substr(strtotime(time_now()), -3, 3);

        return $idCus;
    }

    protected function checkPhoneBeforeUpdate($userId, $phone)
    {
        return $this->_model
                    ->where('phone', $phone)
                    ->where('id', '<>', $userId)
                    ->first();
    }
}