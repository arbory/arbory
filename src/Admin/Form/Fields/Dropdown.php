<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\OptionFieldRenderer;
use Illuminate\Support\Collection;

class Dropdown extends AbstractField
{
    /**
     * @var Collection
     */
    protected $options;

    /**
     * @var int|string|null
     */
    protected $defaultValue = null;

    /**
     * @param string $name
     * @param DropdownOption[]|mixed[] $options
     * @param int|string|null $defaultValue
     */
    public function __construct( string $name, $options, $defaultValue = null )
    {
        $this->options = new Collection();
        $this->defaultValue = $defaultValue;

        foreach( $options as $option )
        {
            $this->options->put( $option->getValue(), $option );
        }

        parent::__construct( $name );
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Element
     */
    public function render()
    {
        $optionalFieldRenderer = new OptionFieldRenderer( $this );

        $optionalFieldRenderer->setSelected( $this->defaultValue );

        return $optionalFieldRenderer->render();
    }

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        return $this->options->toArray();
    }
}
