<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'max:100'],
            'password' => ['nullable', 'max:100'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "error" => $validator->getMessageBag()
        ],400));
    }

}
