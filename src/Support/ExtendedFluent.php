<?php

namespace Arbory\Base\Support;

use Traversable;
use ArrayIterator;
use IteratorAggregate;
use Illuminate\Support\Fluent;

class ExtendedFluent extends Fluent implements IteratorAggregate
{
    /**
     * @return static
     */
    public function replace(array $data = []): self
    {
        $this->attributes = $data;

        return $this;
    }

    /**
     * @return static
     */
    public function add(array $data = []): self
    {
        $this->attributes = array_merge($this->attributes, $data);

        return $this;
    }

    public function has(string $attribute): bool
    {
        return $this->offsetExists($attribute);
    }

    /**
     * @param $value
     * @return static
     */
    public function set(string $attribute, $value): self
    {
        $this->offsetSet($attribute, $value);

        return $this;
    }

    public function isEmpty(?string $attribute = null): bool
    {
        if ($attribute) {
            return $this->isEmptyDeep($this->get($attribute));
        }

        return $this->isEmptyDeep($this->attributes);
    }

    protected function isEmptyDeep(mixed $item): bool
    {
        if (! is_array($item)) {
            return blank($item);
        }

        return count(array_filter($item, fn($item) => ! $this->isEmptyDeep($item), ARRAY_FILTER_USE_BOTH)) === 0;
    }

    /**
     * Retrieve an external iterator.
     *
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *                     <b>Traversable</b>
     *
     * @since 5.0.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes);
    }
}
