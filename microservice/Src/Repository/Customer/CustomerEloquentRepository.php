<?php

namespace MicroService\Src\Repository\Customer;

use App\Models\Customer;
use MicroService\Src\Repository\AbstractEloquentRepository;

class CustomerEloquentRepository extends AbstractEloquentRepository
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

    protected function findEmail($data)
    {
        $result = $this->_model->where('email', $data)->first();
        
        return $result;
    }

     /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function updatePassword($id, array $attributes)
    {
        $result = $this->_model->where('id', $id)
                        ->update($attributes);

        return $result;
    }

    // processing send mail
    public function sendMailForgotPassword($dataBody, $request, $user, $newPassword)
    {
        $mailTemplate = 'email.forgot_password';
        $options = [
            'title' => 'Thay đổi mật khẩu',
            'to_email' => $request->get('email'),
            'full_name' => $user->last_name .' '. $user->first_name,
            'new_password' => $newPassword,
        ];
        // process send mail
        mailer($mailTemplate, $options, function ($message) use ($options) {
            $message->subject('Thay đổi mật khẩu');
            $message->to($options['to_email']);
        }, config('queue.priority.high'));
    }
}