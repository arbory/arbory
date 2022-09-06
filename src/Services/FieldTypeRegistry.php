<?php

namespace Arbory\Base\Services;

use ReflectionException;
use InvalidArgumentException;
use ReflectionMethod;
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

    protected array $reservedTypes = [];

    /**
     * FieldTypeRegistry constructor.
     *
     *
     * @throws ReflectionException
     */
    public function __construct(protected Container $app)
    {
        $this->fieldTypes = new Collection();
        $this->reservedTypes = $this->getReservedMethods(FieldSet::class);
    }

    /**
     * @return $this
     */
    public function register(string $type, string $class): self
    {
        if (in_array(strtolower($type), $this->reservedTypes, true)) {
            $message = 'The name '.$type.' is already being used by FieldSet class for a method';
            throw new InvalidArgumentException($message);
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

    public function findByType(string $type): ?string
    {
        return $this->fieldTypes->get($type);
    }

    public function has(string $type): bool
    {
        return $this->fieldTypes->has($type);
    }

    /**
     * Resolves a field class instance.
     */
    public function resolve(string $type, array $parameters): FieldInterface
    {
        $fieldClass = $this->findByType($type);

        if (! $fieldClass || ! class_exists($fieldClass)) {
            throw new InvalidArgumentException("Could not resolve a field for a type '{$type}'");
        }

        return $this->app->make($fieldClass, $this->bindParametersByIndex($fieldClass, $parameters));
    }

    /**
     * Finds any accessible functions which are defined in class.
     *
     *
     * @throws ReflectionException
     */
    protected function getReservedMethods(mixed $class): array
    {
        $reflection = new ReflectionClass($class);

        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        $reservedMethods = [];

        foreach ($methods as $method) {
            $reservedMethods[] = strtolower($method->getName());
        }

        return $reservedMethods;
    }

    /**
     * Builds an dictionary of parameters by name for an class from index based parameter list.
     *
     * @return array
     *
     * @throws ReflectionException
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
