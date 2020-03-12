<?php

namespace Arbory\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class InlineEditRequest.
 */
class InlineEditRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'column' => 'required',
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
