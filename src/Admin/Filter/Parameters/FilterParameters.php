<?php


namespace Arbory\Base\Admin\Filter\Parameters;

use Arbory\Base\Admin\Filter\FilterItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

class FilterParameters extends Fluent
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
    public function setNamespace(?string $namespace): FilterParameters
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
     * @param array $data
     * @return FilterParameters
     */
    public function replace(array $data = []): FilterParameters
    {
        $this->attributes = $data;

        return $this;
    }

    /**
     * @param array $data
     * @return FilterParameters
     */
    public function add(array $data = []): FilterParameters
    {
        $this->attributes = array_merge($this->attributes, $data);

        return $this;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function has(string $attribute): bool
    {
        return $this->offsetExists($attribute);
    }

    /**
     * @param string $attribute
     * @param $value
     * @return FilterParameters
     */
    public function set(string $attribute, $value): FilterParameters
    {
        $this->offsetSet($attribute, $value);

        return $this;
    }

    /**
     * @param string|null $attribute
     * @return bool
     */
    public function isEmpty(?string $attribute = null): bool
    {
        if($attribute) {
            return $this->isEmptyDeep($this->get($attribute));
        }

        return $this->isEmptyDeep($this->attributes);
    }

    /**
     * @param string|null $fieldName
     * @return array
     */
    public function getErrors(?string $fieldName = null): array
    {
        if($fieldName !== null) {
            return Arr::get($this->errors, $fieldName);
        }

        return $this->errors;
    }

    /**
     * @param array $errors
     *
     * @return FilterParameters
     */
    public function setErrors(array $errors): FilterParameters
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @param string $fieldName
     * @param array $errors
     * @return FilterParameters
     */
    public function addErrors(string $fieldName, array $errors): FilterParameters
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

    /**
     * @param mixed $item
     * @return bool
     */
    protected function isEmptyDeep($item): bool {
        if(! is_array($item)) {
            return blank($item);
        }

        return count(array_filter($item, function($item) {
            return ! $this->isEmptyDeep($item);
        }, ARRAY_FILTER_USE_BOTH)) === 0;
    }
}