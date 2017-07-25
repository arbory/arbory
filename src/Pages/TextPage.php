<?php

namespace Arbory\Base\Pages;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Form\Fields\Richtext;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TextPage
 * @package Arbory\Base\Pages
 */
class TextPage extends Model implements PageInterface
{
    /**
     * @var array
     */
    protected $fillable = [ 'html' ];

    public function prepareFieldSet( FieldSet $fieldSet )
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
