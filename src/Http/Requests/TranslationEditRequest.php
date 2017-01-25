<?php

namespace CubeSystems\Leaf\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationEditRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'namespace' => 'required',
            'group' => 'required',
            'item' => 'required',
            'page' => 'required',
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
