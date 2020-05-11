<?php

namespace Arbory\Base\Admin\Filter\Parameters;

use Illuminate\Support\Arr;
use Arbory\Base\Support\ExtendedFluent;
use Arbory\Base\Admin\Filter\FilterItem;

class FilterParameters extends ExtendedFluent
{
    /**
     * @var string
     */
    protected $namespace = 'filter';

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @return string
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string|null $namespace
     *
     * @return FilterParameters
     */
    public function setNamespace(?string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param FilterItem $filterItem
     * @return mixed
     */
    public function getFromFilter(FilterItem $filterItem)
    {
        return $this->get($filterItem->getName());
    }

    /**
     * @param string|null $fieldName
     * @return array
     */
    public function getErrors(?string $fieldName = null): array
    {
        if ($fieldName !== null) {
            return Arr::get($this->errors, $fieldName);
        }

        return $this->errors;
    }

    /**
     * @param array $errors
     *
     * @return FilterParameters
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @param string $fieldName
     * @param array $errors
     * @return FilterParameters
     */
    public function addErrors(string $fieldName, array $errors): self
    {
        $this->errors[$fieldName] = $errors;

        return $this;
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    public function hasError(string $fieldName): bool
    {
        return Arr::has($this->errors, $fieldName);
    }
}
