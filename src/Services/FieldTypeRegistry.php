<?php

namespace Arbory\Base\Services;

use ReflectionClass;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Form\Fields\FieldInterface;

class FieldTypeRegistry
{
    /**
     * @var Collection
     */
    protected $fieldTypes;

    /**
     * @var
     */
    protected $reservedTypes = [];
    /**
     * @var Container
     */
    protected $app;

    /**
     * FieldTypeRegistry constructor.
     *
     * @param Container $app
     *
     * @throws \ReflectionException
     */
    public function __construct(Container $app)
    {
        $this->fieldTypes = new Collection();
        $this->reservedTypes = $this->getReservedMethods(FieldSet::class);
        $this->app = $app;
    }

    /**
     * @param string $type
     * @param string $class
     * @return $this
     */
    public function register(string $type, string $class): self
    {
        if (in_array(strtolower($type), $this->reservedTypes, true)) {
            $message = 'The name '.$type.' is already being used by FieldSet class for a method';
            throw new \InvalidArgumentException($message);
        }

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
     * @param string $type
     * @return string|null
     */
    public function findByType($type): ?string
    {
        return $this->fieldTypes->get($type);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function has($type): bool
    {
        return $this->fieldTypes->has($type);
    }

    /**
     * Resolves a field class instance.
     *
     * @param string $type
     * @param array $parameters
     *
     * @return FieldInterface
     */
    public function resolve($type, array $parameters): FieldInterface
    {
        $fieldClass = $this->findByType($type);

        if (! $fieldClass || ! class_exists($fieldClass)) {
            throw new \InvalidArgumentException("Could not resolve a field for a type '{$type}'");
        }

        return $this->app->make($fieldClass, $this->bindParametersByIndex($fieldClass, $parameters));
    }

    /**
     * Finds any accessible functions which are defined in class.
     *
     * @param mixed $class
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getReservedMethods($class)
    {
        $reflection = new ReflectionClass($class);

        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        $reservedMethods = [];

        foreach ($methods as $method) {
            $reservedMethods[] = strtolower($method->getName());
        }

        return $reservedMethods;
    }

    /**
     * Builds an dictionary of parameters by name for an class from index based parameter list.
     *
     * @param string $class
     * @param array  $parameters
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function bindParametersByIndex(string $class, array $parameters)
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        $reflectionParameters = $constructor->getParameters();

        $out = [];

        foreach ($parameters as $key => $parameter) {
            $reflectionParameter = $reflectionParameters[$key];

            $out[$reflectionParameter->getName()] = $parameter;
        }

        return $out;
    }
}
