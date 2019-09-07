<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class AbstractApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $this->body = $validator->errors();
        throw new HttpResponseException(response()->json($this->result(), 422));
    }
}
