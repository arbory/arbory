<?php

namespace CubeSystems\Leaf\Pages;

use CubeSystems\Leaf\Fields\Text;
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
     * @return Text
     */
    public function getHtmlAttribute()
    {
        // TODO: replace HTML placeholders - links, images, embed, etc.

        return ( new Text( 'html' ) )->setValue( $this->getAttributeFromArray( 'html' ) );
    }
}
