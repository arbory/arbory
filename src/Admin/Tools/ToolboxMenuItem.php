<?php

namespace Arbory\Base\Admin\Tools;

/**
 * Class AbstractAction.
 */
class ToolboxMenuItem
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $classes = [];

    /**
     * Item constructor.
     * @param string $title
     * @param string $url
     */
    public function __construct($title, $url)
    {
        $this->setTitle($title);
        $this->setUrl($url);
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
    public function setTitle($title)
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
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return implode(' ', $this->classes);
    }

    /**
     * @return ToolboxMenuItem
     */
    public function dialog()
    {
        $this->classes[] = 'ajaxbox';

        return $this;
    }

    /**
     * @return ToolboxMenuItem
     */
    public function danger()
    {
        $this->classes[] = 'danger';

        return $this;
    }
}
