<?php

namespace CubeSystems\Leaf\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormValidationRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'meta_author' => 'required',
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