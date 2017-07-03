<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Admin\Form\Fields\SpriteIcon;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

class IconPickerRenderer extends SelectFieldRenderer
{
    /**
     * @return Element
     */
    public function render()
    {
        $field = new FieldRenderer();
        $field->setType( 'select' );
        $field->setName( $this->field->getName() );
        $field->setLabel( $this->getLabel() );

        $input = $this->getSelectInput();

        $field->setValue( $input . $this->getIconSelectElement() );

        return $field->render()->addClass( 'type-icon-picker' );
    }

    /**
     * @return Element
     */
    protected function getIconSelectElement()
    {
        /** @var SpriteIcon $field */
        $field = $this->field;
        $items = Html::ul()->addClass( 'items' );

        foreach( $field->getOptions() as $option )
        {
            $items->append( Html::li( $this->getSvgIconElement( $option ) ) );
        }

        return Html::div(
            Html::div(
                $this->getSvgIconElement( $field->getValue() )
            )->addClass( 'selected' )
            . $items
        )->addClass( 'contents' );
    }

    /**
     * @param string $id
     * @return Element
     */
    protected function getSvgIconElement( $id )
    {
        if( !$id )
        {
            return Html::svg();
        }

        /** @var SpriteIcon $field */
        $field = $this->field;

        $iconNode = $field->getIconContent( $id );
        $content = (string) $iconNode->path->asXML();

        $attributes = $iconNode->attributes();
        $width = (int) $attributes->width;
        $height = (int) $attributes->height;

        $icon = Html::span( Html::svg( $content )
            ->addAttributes( [
                'width' => $width,
                'height' => $height,
                'viewBox' => sprintf( '0 0 %d %d', $width, $height ),
                'role' => 'presentation',
            ] ) )->addClass( 'icon' );

        return Html::div( $icon . Html::span( $id ) )->addClass( 'element' );
    }
}