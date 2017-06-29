<?php

namespace CubeSystems\Leaf\Admin\Form;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Validator
 * @package CubeSystems\Leaf\Admin\Form
 */
class Validator extends FormRequest
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @param array $rules
     * @return $this
     */
    public function setRules( array $rules )
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }


}
