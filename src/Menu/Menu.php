<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Menu
{
    const COOKIE_NAME_MENU = 'menu';

    /**
     * @var Collection
     */
    protected $items;

    /**
     * @param  Collection|null  $items
     */
    public function __construct(Collection $items = null)
    {
        $this->items = $items ?: new Collection();
    }

    /**
     * @param  AbstractItem  $item
     * @return void
     */
    public function addItem(AbstractItem $item)
    {
        $this->items->push($item);
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return Elements\Element
     */
    public function render()
    {
        $list = Html::ul()->addClass('block');

        foreach ($this->getItems() as $item) {
            $name = Str::snake($item->getTitle());
            $collapsed = $this->getMenuItemCookie($name);

            if (! $this->hasMenuItemCookie($name)) {
                $collapsed = true;
            }

            /** @var AbstractItem $item */
            if (! $item) {
                continue;
            }

            $li = Html::li()
                ->addAttributes(['data-name' => $name]);

            if ($item->isAccessible()) {
                $list->append(
                    $item->render($li)->addClass($collapsed ? 'collapsed' : '')
                );
            }
        }

        return $list;
    }

    /**
     * @return array
     */
    protected function getMenuCookie()
    {
        $menuCookie = Arr::get($_COOKIE, self::COOKIE_NAME_MENU);

        if (is_array($menuCookie)) {
            return $menuCookie;
        }

        return (array) json_decode($menuCookie, true);
    }

    /**
     * @param  string  $name
     * @return bool
     */
    protected function hasMenuItemCookie(string $name)
    {
        return Arr::has($this->getMenuCookie(), $name);
    }

    /**
     * @param  string  $name
     * @return bool
     */
    protected function getMenuItemCookie(string $name)
    {
        return Arr::get($this->getMenuCookie(), $name);
    }
}
