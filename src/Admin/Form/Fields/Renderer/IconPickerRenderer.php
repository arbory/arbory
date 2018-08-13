<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\SpriteIcon;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Arbory\Base\Html\HtmlString;

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

        $field->setValue( [ $input,  $this->getIconSelectElement() ] );

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

        return Html::div([
            Html::div(
                $this->getSvgIconElement($field->getValue())
            )->addClass('selected'),
            $items
        ])->addClass('contents');
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

        if ( !$iconNode )
        {
            return Html::div()->addClass( 'element' );
        }

        $node = simplexml_load_string($iconNode->asXML());
        $path = array_first($node->xpath('/symbol//path[@d]'));

        if( $path )
        {
            $content = $path->asXML();
        }
        else 
        {
            return Html::div()->addClass( 'element' );
        }

        $attributes = $iconNode->attributes();
        $width = (int) $attributes->width;
        $height = (int) $attributes->height;

        $icon = Html::span( Html::svg( new HtmlString($content) )
            ->addAttributes( [
                'width' => $width,
                'height' => $height,
                'viewBox' => sprintf( '0 0 %d %d', $width, $height ),
                'role' => 'presentation',
            ] ) )->addClass( 'icon' );

        return Html::div( [ $icon, Html::span( $id ) ] )->addClass( 'element' );
    }
}
