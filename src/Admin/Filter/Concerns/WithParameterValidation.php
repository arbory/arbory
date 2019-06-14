<?php


namespace Arbory\Base\Admin\Filter\Concerns;


use Arbory\Base\Admin\Filter\Parameters;

interface WithParameterValidation
{
    /**
     * TODO: Laravel validator & Validation support for multi level parameters
     *
     * @param Parameters $parameters
     * @return array
     */
    public function rules(Parameters $parameters): array;
}