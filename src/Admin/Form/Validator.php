<?php

namespace Arbory\Base\Admin\Form;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Validator.
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
    public function setRules(array $rules)
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

    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $input = $this->all();
        $destroyed = [];

        foreach (array_dot($input) as $namespace => $value) {
            if (ends_with($namespace, '._destroy') && $value === 'true') {
                $destroyed[] = substr($namespace, 0, strrpos($namespace, '.'));
            }
        }

        array_forget($input, $destroyed);

        $this->replace($input);
    }
}
