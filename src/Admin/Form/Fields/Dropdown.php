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
     * @var string|int
     */
    protected $defaultValue = null;

    /**
     * @param string $name
     * @param DropdownOption[]|mixed[] $options
     * @param int|null $defaultValue
     */
    public function __construct( string $name, $options, int $defaultValue = null )
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
        $model = $this->getModel();

        $currentValue = $model->{$this->getName()};

        $currentOption = null;

        if( $this->options->has( $currentValue ) )
        {
            $currentOption = $this->options->get( $currentValue );
        }

        if( $currentOption === null && $this->defaultValue !== null )
        {
            $currentOption = $this->options->get( $this->defaultValue );
        }

        if( $currentOption !== null )
        {
            $currentOption->setSelected( true );
        }

        return ( new OptionFieldRenderer( $this ) )->render();
    }

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        return $this->options->toArray();
    }
}
