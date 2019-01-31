<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class Link extends HasOne
{
    protected $style = 'nested';

    /**
     * @param string $name
     */
    public function __construct( string $name )
    {
        $fieldSetCallback = function( FieldSet $fieldSet )
        {
            $fieldSet->text( 'href' );
            $fieldSet->text( 'title' );
            $fieldSet->checkbox( 'new_tab' );
        };

        parent::__construct( $name, $fieldSetCallback );
    }

    /**
     * @return Element
     */
    public function render()
    {
        $item = $this->getValue() ?: $this->getRelatedModel();

        $block = Html::div()->addClass( 'link-body' );

        $fieldSetHtml = Html::fieldset()->addClass( 'item' );

        foreach( $this->getRelationFieldSet( $item )->getFields() as $field )
        {
            $fieldSetHtml->append( $field->render() );
        }

        return $block->append( $fieldSetHtml );
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $rules = $this->rules[ 0 ] ?? null;

        if( $rules )
        {
            $this->fieldSetCallback = function( FieldSet $fieldSet ) use ( $rules )
            {
                $fieldSet->text( 'href' )->rules( $rules );
                $fieldSet->text( 'title' )->rules( $rules );
                $fieldSet->checkbox( 'new_tab');
            };
        }

        unset( $this->rules );

        return parent::getRules();
    }
}
