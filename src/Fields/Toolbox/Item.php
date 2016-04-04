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
     * @param $name
     * @return $this
     */
    public function setTitle( $name )
    {
        $this->title = $name;

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
