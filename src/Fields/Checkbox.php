<?php

namespace CubeSystems\Leaf\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

/**
 * Class Checkbox
 * @package CubeSystems\Leaf\Fields
 */
class Checkbox extends AbstractField
{
    /**
     * @var int|string
     */
    protected $checkedValue;

    /**
     * @var int|string
     */
    protected $uncheckedValue;

    /**
     * Checkbox constructor.
     * @param string $name
     * @param int|string $checkedValue
     * @param int|string $uncheckedValue
     */
    public function __construct( $name, $checkedValue = 1, $uncheckedValue = 0 )
    {
        $this->checkedValue = $checkedValue;
        $this->uncheckedValue = $uncheckedValue;

        parent::__construct( $name );
    }

    /**
     * @param array $attributes
     * @return View
     */
    public function render( array $attributes = [] )
    {
        if( $this->isForList() )
        {
            $model = $this->getModel();

            return view( $this->getViewName(), [
                'field' => $this,
                'attributes' => $attributes,
                'checked_value' => $this->checkedValue,
                'unchecked_value' => $this->uncheckedValue,
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
                'checked_value' => $this->checkedValue,
                'unchecked_value' => $this->uncheckedValue,
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
    public function postUpdate( Model $model, array $input = [] )
    {
        if( !isset( $input[$this->getName()] ) && $input[$this->getName()] != $this->checkedValue )
        {
            $input[$this->getName()] = $this->uncheckedValue;
        }
        else
        {
            $input[$this->getName()] = $this->checkedValue;
        }

        return parent::postUpdate( $model, $input );
    }
}
