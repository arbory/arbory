<?php

namespace CubeSystems\Leaf\Fields\Toolbox;

use CubeSystems\Leaf\Fields\Toolbox;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractAction
 * @package CubeSystems\Leaf\Fields\ToolboxActions
 */
class Item
{
    /**
     * @var Toolbox
     */
    protected $toolbox;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $url;

    public function __construct( $title )
    {
        $this->setTitle( $title );
    }

    /**
     * @return Toolbox
     */
    public function getToolbox()
    {
        return $this->toolbox;
    }

    /**
     * @param Toolbox $toolbox
     * @return $this
     */
    public function setToolbox( $toolbox )
    {
        $this->toolbox = $toolbox;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl( $url )
    {
        $this->url = $url;

        return $this;
    }

}
