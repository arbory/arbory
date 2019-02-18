<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Html\Elements\Content;

class LayoutContent
{
    /**
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @var mixed
     */
    protected $content;

    public function __construct()
    {
        $this->content = new Content();
    }

    public function push($content)
    {
        $this->content->push($content);

        return $this;
    }

    public function prepend($content)
    {
        $this->content->prepend($content);

        return $this;
    }

    public function insert(LayoutInterface $layout)
    {
//        dump($this->content);
        $this->content = $layout->setContent($this->getLayout());

        $this->setLayout($layout);
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return LayoutInterface
     */
    public function getLayout(): LayoutInterface
    {
        return $this->layout;
    }

    /**
     * @param LayoutInterface $layout
     *
     * @return LayoutContent
     */
    public function setLayout(LayoutInterface $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

}