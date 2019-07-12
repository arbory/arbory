<?php

namespace Arbory\Base\Menu;

class MenuFactory
{
    /**
     * @var MenuItemFactory
     */
    protected $menuItemFactory;

    /**
     * @param MenuItemFactory $menuItemFactory
     */
    public function __construct(MenuItemFactory $menuItemFactory)
    {
        $this->menuItemFactory = $menuItemFactory;
    }

    /**
     * @param mixed[] $items
     * @return Menu
     * @throws \DomainException
     */
    public function build(array $items)
    {
        foreach ($items as $key => &$item) {
            $title = ! is_numeric($key) ? $key : null;
            $item = $this->menuItemFactory->build($item, $title);
        }

        return new Menu(collect($items));
    }
}
