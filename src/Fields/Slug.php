<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use CubeSystems\Leaf\Node;
use Illuminate\Database\Eloquent\Model;

class Slug extends AbstractField
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
        $baseUrl = url( '/' );
        $uri = $this->getModel()->getUri();

        $label = Html::label( $this->getLabel() )->addAttributes( [ 'for' => $this->getName() ] );

        $input = Html::input()
            ->setName( $this->getNameSpacedName() )
            ->setValue( $this->getValue() )
            ->addClass( 'text' )
            ->addAttributes( [ 'data-generator-url' => $this->getSlugGeneratorUrl() ] );

        $button = Html::button( Html::i()->addClass( 'fa fa-keyboard-o' ) )
            ->addClass( 'button only-icon secondary generate' )
            ->addAttributes( [
                'type' => 'button',
                'title' => trans('leaf.fields.slug.suggest_slug'),
                'autocomplete' => 'off',
            ] );

        return Html::div( [
            Html::div( $label )->addClass( 'label-wrap' ),
            Html::div( [ $input, $button ] )->addClass( 'value' ),
            Html::div( Html::link( [ $baseUrl . '/', Html::span( $uri ) ] ) )->addClass( 'link' ),
        ] )->addClass( 'field type-text' )->addAttributes( [ 'data-name' => 'slug' ] );
    }

    /**
     * @return Node|Model
     */
    public function getModel()
    {
        return parent::getModel();
    }

    /**
     * @return string
     */
    private function getSlugGeneratorUrl()
    {
        $model = $this->getModel();

        $params = [
            'model' => $this->getController()->getSlug(),
            'api' => 'slug_generator',
        ];

        if( $model )
        {
            $params['id'] = $model->getId();
            $params['parent_id'] = $model->getParentId();
        }

        return route( 'admin.model.api', array_filter( $params ) );
    }
}
