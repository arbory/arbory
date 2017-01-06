<?php

namespace CubeSystems\Leaf\Fields;

use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

/**
 * Class Dropdown
 * @package CubeSysetms\Leaf\Fields
 */
class Dropdown extends AbstractField
{
    /**
     * @var DropdownOption[]
     */
    protected $options = [];

    /**
     * @var string|int
     */
    protected $defaultValue = null;

    /**
     * Dropdown constructor.
     * @param string $name
     * @param DropdownOption[] $options
     * @param null $defaultValue
     */
    public function __construct( $name, $options, $defaultValue = null )
    {
        $this->options = [];
        foreach( $options as $option )
        {
            $this->options[$option->getValue()] = $option;
        }

        $this->defaultValue = $defaultValue;

        parent::__construct( $name );
    }

    /**
     * @param array $attributes
     * @return Factory|View|null
     */
    public function render( array $attributes = [] )
    {
        $model = $this->getModel();

        $currentValue = $model->{$this->getName()};

        $currentOption = null;

        if( isset( $this->options[$currentValue] ) )
        {
            $currentOption = $this->options[$currentValue];
        }

        if( $currentOption == null && $this->defaultValue !== null )
        {
            $currentOption = $this->options[$this->defaultValue];
        }

        if( $currentOption != null )
        {
            $currentOption->setSelected( true );
        }

        if( $this->isForList() )
        {
            return view( $this->getViewName(), [
                'field' => $this,
                'attributes' => $attributes,
                'current_option' => $currentOption,
                'url' => route( 'admin.model.edit', [
                    $this->getController()->getSlug(),
                    $model->getKey()
                ] ),
            ] );
        }
        elseif( $this->isForForm() )
        {
            return view( $this->getViewName(), [
                'field' => $this,
                'attributes' => $attributes,
                'options' => $this->options,
                'current_option' => $currentOption
            ] );
        }
        else
        {
            return null;
        }
    }

    /**
     * @param Model $model
     * @param array $input
     * @return null
     */
    public function afterModelSave( Model $model, array $input = [] )
    {
        $selectedValue = $input[$this->getName()];

        if( !isset( $this->options[$selectedValue] ) )
        {
            throw new \RuntimeException( 'Bad select field value for "' . $this->getInputName() . '"' );
        }

        $selectedOption = $this->options[$selectedValue];
        $selectedOption->setSelected( true );

        return parent::postUpdate( $model, $input );
    }
}
