<?php

namespace CubeSystems\Leaf\Http\Requests;

use CubeSystems\Leaf\Auth\Users\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Sentinel;

class UpdateUserRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'resource.first_name' => 'required',
            'resource.last_name' => 'required',
            'resource.roles' => '',
            'resource.password' => 'required|min:6|confirmed',
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        // TODO:
        return true;
    }
}
