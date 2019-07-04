<?php


namespace Arbory\Base\Support;

use Illuminate\Support\Fluent;

class ExtendedFluent extends Fluent
{
    /**
     * @param array $data
     * @return static
     */
    public function replace(array $data = []): self
    {
        $this->attributes = $data;

        return $this;
    }

    /**
     * @param array $data
     * @return static
     */
    public function add(array $data = []): self
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
     * @return static
     */
    public function set(string $attribute, $value): self
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
        if ($attribute) {
            return $this->isEmptyDeep($this->get($attribute));
        }

        return $this->isEmptyDeep($this->attributes);
    }

    /**
     * @param mixed $item
     * @return bool
     */
    protected function isEmptyDeep($item): bool
    {
        if (!is_array($item)) {
            return blank($item);
        }

        return count(array_filter($item, function ($item) {
                return !$this->isEmptyDeep($item);
            }, ARRAY_FILTER_USE_BOTH)) === 0;
    }
}