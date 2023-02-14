<?php

namespace Arbory\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

/**
 * Class LoginRequest.
 */
class LoginRequest extends FormRequest
{

    public function rules(): array
    {
        if ($this->isNotFilled('2fa_code') && Route::current()->getName() === 'admin.login.attempt') {
            return [
                'user.email' => 'required|email',
                'user.password' => 'required',
            ];
        }

        return [
            '2fa_code' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'user.email' => trans('arbory::security.email'),
            'user.password' => trans('arbory::security.password'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
