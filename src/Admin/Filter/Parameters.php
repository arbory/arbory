<?php


namespace Arbory\Base\Admin\Filter;


// TODO: Parameter collectors?
// TODO: Preprocessor (when using a more custom query string format)
// TODO: Default values
// TODO: Maybe allowed/disallowed values (probably using callable)
use Illuminate\Support\Fluent;

class Parameters extends Fluent
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
     * @return Parameters
     */
    public function setNamespace(?string $namespace): Parameters
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param array $data
     * @return Parameters
     */
    public function replace(array $data = []): Parameters
    {
        $this->attributes = $data;

        return $this;
    }
}