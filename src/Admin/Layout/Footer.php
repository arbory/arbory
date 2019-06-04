<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Footer.
 */
class Footer implements Renderable
{
    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var Collection
     */
    protected $rows;

    /**
     * Footer constructor.
     * @param string|null $type
     */
    public function __construct($type = null)
    {
        $this->type = $type;
        $this->rows = new Collection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @return Collection
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return Element
     */
    public function render()
    {
        $footer = Html::footer();

        foreach ($this->getRows() as $row) {
            $footer->append($row->render());
        }

        if ($this->type) {
            $footer->addClass($this->type);
        }

        return $footer;
    }
}
