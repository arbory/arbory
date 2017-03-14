<?php

namespace CubeSystems\Leaf\Pages;

use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Admin\Form\Fields\Richtext;
use CubeSystems\Leaf\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TextPage
 * @package CubeSystems\Leaf\Pages
 */
class TextPage extends Model implements PageInterface
{
    /**
     * @var array
     */
    protected $fillable = [ 'html' ];

    public function getFieldSet( FieldSet $fieldSet )
    {
        $fieldSet->add( new Richtext( 'html' ) );
    }

    /**
     * @return Element
     */
    public function getHtmlAttribute()
    {
        // TODO: replace HTML placeholders - links, images, embed, etc.

        return $this->getAttributeFromArray( 'html' ) ;

//        return ( new Richtext( 'html' ) )->setValue( $this->getAttributeFromArray( 'html' ) );
    }
}
