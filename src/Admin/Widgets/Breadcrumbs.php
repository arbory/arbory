<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Breadcrumbs.
 */
class Breadcrumbs implements Renderable
{
    /**
     * @var Collection
     */
    protected $items;

    /**
     * Breadcrumbs constructor.
     */
    public function __construct()
    {
        $this->items = new Collection();
        $this->addItem(trans('arbory::breadcrumbs.home'), route('admin.dashboard.index'));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param $title
     * @param $url
     * @return Breadcrumbs
     */
    public function addItem($title, $url)
    {
        $this->items->push([
            'title' => $title,
            'url' => $url,
        ]);

        return $this;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        $total = $this->items->count();

        $list = $this->items->map(function (array $item, $key) use ($total) {
            $listItem = Html::li(
                Html::link($item['title'])
                    ->addAttributes([
                        'href' => $item['url'],
                    ])
            );

            if ($key !== $total - 1) {
                $listItem->append(Html::i('arrow_right')->addClass('mt-icon'));
            }

            return $listItem;
        });

        return Html::nav(
            Html::ul($list->toArray())->addClass('block breadcrumbs')
        );
    }
}
