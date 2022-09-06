<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Group extends AbstractItem
{
    /**
     * @var Collection
     */
    protected $children;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->children = new Collection();
    }

    public function render(Element $parentElement): Element
    {
        $ul = Html::ul()->addClass('block');

        foreach ($this->getChildren() as $child) {
            /** @var AbstractItem $child */
            $li = Html::li()->addAttributes(['data-name' => Str::snake($child->getTitle())]);

            if ($child->isAccessible()) {
                $child->render($li);

                if ($child->isActive()) {
                    $li->addClass('active');
                }

                $ul->append($li);
            }
        }

        return
            $parentElement
                ->append(
                    Html::span([
                        Html::abbr($this->getAbbreviation())->addAttributes(['title' => $this->getTitle()]),
                        Html::span($this->getTitle())->addClass('name'),
                        Html::span(Html::button(Html::i('arrow_drop_up')->addClass('mt-icon'))
                            ->addAttributes(['type' => 'button']))
                            ->addClass('collapser'),
                    ])->addClass('trigger ' . ($this->isActive() ? 'active' : ''))
                )
                ->append($ul);
    }

    public function isActive(): bool
    {
        return (bool) $this->getChildren()->first(fn (Item $item) => $item->isActive());
    }

    /**
     * @return void
     */
    public function addChild(AbstractItem $child)
    {
        $this->children->push($child);
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children)
    {
        $this->children = $children;
    }

    public function isAccessible(): bool
    {
        foreach ($this->getChildren() as $item) {
            if ($item->isAccessible()) {
                return true;
            }
        }

        return false;
    }
}
