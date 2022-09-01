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
     * @return $this
     */
    public function options(Collection|array $options)
    {
        if (is_array($options)) {
            $options = new Collection($options);
        }

        $this->options = $options;

        return $this;
    }

    public function getOptions(): Collection
    {
        return $this->options;
    }
}
