<?php

namespace CubeSystems\Leaf\Pages;

use CubeSystems\Leaf\Fields\Richtext;
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

    /**
     * @return Element
     */
    public function getHtmlAttribute()
    {
        // TODO: replace HTML placeholders - links, images, embed, etc.

        return ( new Richtext( 'html' ) )->setValue( $this->getAttributeFromArray( 'html' ) );
    }
}
