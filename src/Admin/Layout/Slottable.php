<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;

trait Slottable
{
    /**
     * @var Content[]|Renderable[]
     */
    protected $slots = [];

    public function registerSlot($slot, $content = null)
    {
        $this->slots[$slot] = $content ?: new Content();
    }

    /**
     * @param $slot
     * @param $content
     *
     * @return $this
     */
    public function appendSlot($slot, $content)
    {
        if(array_key_exists($slot, $this->slots)) {
            $this->slots[$slot]->push($content);

            return $this;
        }

        throw new \InvalidArgumentException("Unknown slot '{$slot}'");
    }

    public function renderSlot($slot)
    {
        if(array_key_exists($slot, $this->slots)) {
            return $this->slots[$slot]->render();
        }

        return null;
    }
}