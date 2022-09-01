<?php

namespace Arbory\Base\Menu;

use DomainException;
class MenuFactory
{
    public function __construct(protected MenuItemFactory $menuItemFactory)
    {
    }

    /**
     * @param  mixed[]  $items
     * @return Menu
     *
     * @throws DomainException
     */
    public function build(array $items)
    {
        foreach ($items as $key => &$item) {
            $title = is_numeric($key) ? null : $key;
            $item = $this->menuItemFactory->build($item, $title);
        }

        return new Menu(collect($items));
    }
}
