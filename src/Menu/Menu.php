<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Menu
{
    public const COOKIE_NAME_MENU = 'menu';

    /**
     * @var Collection
     */
    protected $items;

    /**
     * @param Collection|null $items
     */
    public function __construct(Collection $items = null)
    {
        $this->items = $items ?: new Collection();
    }

    /**
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

    public function render(): Element
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

    protected function getMenuCookie(): array
    {
        $menuCookie = Arr::get($_COOKIE, self::COOKIE_NAME_MENU);

        if (is_array($menuCookie)) {
            return $menuCookie;
        }

        return (array) json_decode($menuCookie, true);
    }

    protected function hasMenuItemCookie(string $name): bool
    {
        return Arr::has($this->getMenuCookie(), $name);
    }

    protected function getMenuItemCookie(string $name): bool
    {
        return Arr::get($this->getMenuCookie(), $name, false);
    }
}
