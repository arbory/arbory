<?php

namespace CubeSystems\Leaf\Http\Requests;

use CubeSystems\Leaf\Users\User;
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
        dd(12 );
        return [
            'resource.first_name' => 'required',
            'resource.last_name' => 'required',
            'resource.email' => [
                'required',
                'email',
                Rule::unique( ( new User )->getTable() )->ignore( Sentinel::getUser()->getUserLogin(), 'email' ),
            ],
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
