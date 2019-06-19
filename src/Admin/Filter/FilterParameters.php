<?php


namespace Arbory\Base\Admin\Filter;


// TODO: Parameter collectors?
// TODO: Preprocessor (when using a more custom query string format)
// TODO: Default values
// TODO: Maybe allowed/disallowed values (probably using callable)
use Illuminate\Support\Fluent;

class FilterParameters extends Fluent
{
    protected $namespace = 'filter';

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
}