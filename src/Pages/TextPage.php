<?php

namespace CubeSystems\Leaf\Pages;

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
     * @return string
     */
    public function getHtml()
    {
        // TODO: replace HTML placeholders - links, images, embed, etc.
        return $this->html;
    }
}
