<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Html\Elements\Content;
use Illuminate\Pipeline\Pipeline;

abstract class AbstractLayout
{
    static $LAYOUT_NO = 0;

    /**
     * @var Slot
     */
    protected $root;

    /**
     * @var LayoutInterface[]
     */
    protected $layouts;

    protected $pipeline;

    protected $slots;

    protected $content;

    /**
     * @param      $name
     * @param null $content
     *
     * @return Slot
     */
    public function slot($name, $content = null)
    {
        if($this->root === null)
        {
            $this->root = new Slot('root');
        }

        if(func_num_args() === 1) {
            return $this->root->getChild($name);
        }

        return $this->root->setChild($name, $content);
    }

    public function slots()
    {
        if($this->root === null) {
            return collect();
        }

        return $this->root->children();
    }

    abstract function build();

    public function render()
    {
        $this->build();

        $evaluated = $this;
        
        $this->pipeline()->then();

//        return $this->root->render();
    }

    public function use(LayoutInterface $layout)
    {
        $this->layouts[] = $layout;

        return $this;
    }


    /**
     * Transform the content
     *
     * @return mixed
     */
    public function transform($content)
    {
        return $this->pipeline()
            ->send($content)
            ->then(function($content) {
                return $content;
            });
    }

    public function pipeline():Pipeline
    {
        if($this->pipeline === null)
        {
            $this->pipeline = new Pipeline(app());
        }

        return $this->pipeline
            ->via('apply')
            ->through($this->layouts)
            ->send($this);
    }

    public function push($content, $name = null)
    {
        $name = $name ?: $this->createUniqueName();

        $slot = $this->slot($name, $content);

        $this->content()->push($slot);

        return $this;
    }

    public function prepend($content, $name = null)
    {
        $name = $name ?: $this->createUniqueName();


        $slot = $this->slot($name, $content);

        $this->content()->prepend($slot);

        return $this;
    }

    protected function content():Content
    {
        if($this->content === null)
        {
            $this->content = new Content();
        }

        return $this->content;
    }

    protected function createUniqueName()
    {
        return get_class($this) . "_" . (++self::$LAYOUT_NO);
    }
}