<?php

namespace Arbory\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 * @package Arbory\Base\Http\Requests
 */
class LoginRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'user.email' => 'required|email',
            'user.password' => 'required'
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
