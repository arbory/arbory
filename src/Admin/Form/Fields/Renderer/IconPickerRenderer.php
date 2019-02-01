<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\IconPicker;
use Arbory\Base\Html\Elements\Content;
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
        $input = parent::render();

        $content = [ $input, $this->getIconSelectElement() ];

        return new Content($content);
    }

    /**
     * @return Element
     */
    protected function getIconSelectElement()
    {
        /** @var IconPicker $field */
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

        /** @var IconPicker $field */
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
