<?php

namespace Arbory\Base\Admin\Layout\Footer;

use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Tools.
 */
class Tools implements Renderable
{
    /**
     * @var Collection
     */
    protected $blocks;

    /**
     * Tools constructor.
     */
    public function __construct()
    {
        $this->blocks = new Collection();
    }

    /**
     * @return Collection
     */
    public function blocks()
    {
        return $this->blocks;
    }

    /**
     * @param $name
     * @return Collection
     */
    public function getBlock($name)
    {
        if (! $this->blocks()->has($name)) {
            $this->blocks()->put($name, new Collection());
        }

        return $this->blocks()->get($name);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $tools = Html::div()->addClass('tools');

        foreach ($this->blocks() as $name => $content) {
            $tools->append(
                Html::div($content->toArray())->addClass($name)
            );
        }

        return $tools;
    }
}
