<?php

namespace Arbory\Base\Services;

use Illuminate\Support\Collection;

class FieldTypeRegistry
{
    /**
     * @var Collection
     */
    protected $fieldTypes;

    /**
     * FieldTypeRegistry constructor.
     */
    public function __construct()
    {
        $this->fieldTypes = new Collection();
    }

    /**
     * @param string $type
     * @param string $class
     * @return $this
     */
    public function register(string $type, string $class): self
    {
        $this->fieldTypes->put($type, $class);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getFieldTypes()
    {
        return $this->fieldTypes;
    }

    /**
     * @param $type
     * @return string|null
     */
    public function findByType($type): ?string
    {
        return $this->fieldTypes->get($type);
    }
}
