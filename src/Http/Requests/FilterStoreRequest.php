<?php

namespace Arbory\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FilterStoreRequest.
 */
class FilterStoreRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'filter' => 'required',
            'name' => 'required|max:255',
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
