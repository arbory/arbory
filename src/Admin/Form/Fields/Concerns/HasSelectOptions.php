<?php

namespace Arbory\Base\Admin\Form\Fields\Concerns;

use Illuminate\Support\Collection;

/**
 * Class HasSelectOptions.
 */
trait HasSelectOptions
{
    /**
     * @var Collection
     */
    protected $options;

    /**
     * @param Collection|array $options
     * @return $this
     */
    public function options($options)
    {
        if (is_array($options)) {
            $options = new Collection($options);
        }

        $this->options = $options;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }
}
