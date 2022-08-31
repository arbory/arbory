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
     * @var Collection
     */
    protected $rows;

    /**
     * Footer constructor.
     */
    public function __construct(protected ?string $type = null)
    {
        $this->rows = new Collection();
    }

    /**
     * @return string
     */
    public function __toString(): string
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
