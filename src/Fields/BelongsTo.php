<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Fields\Renderer\OptionFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BelongsTo
 * @package CubeSystems\Leaf\Fields
 */
class BelongsTo extends AbstractField
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return Element
     */
    public function render()
    {
        return ( new OptionFieldRenderer( $this ) )->render();
    }

    /**
     * @return Model
     */
    protected function getRelatedModel()
    {
        return $this->getModel()->{$this->getName()}()->getRelated();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = [];

        $selected = $this->getValue()
            ? $this->getValue()->getKey()
            : null;

        foreach( $this->getRelatedModel()->all() as $item )
        {
            $option = Html::option( (string) $item )->setValue( $item->getKey() );

            if( $selected === $item->getKey() )
            {
                $option->select();
            }

            $options[] = $option;
        }

        return $options;
    }
}
