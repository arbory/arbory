<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Admin\Admin;
use Waavi\Translation\Repositories\TranslationRepository;

class MenuItemFactory
{
    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @var TranslationRepository
     */
    protected $translations;

    /**
     * @param Admin $admin
     * @param TranslationRepository $translations
     */
    public function __construct(Admin $admin, TranslationRepository $translations)
    {
        $this->admin = $admin;
        $this->translations = $translations;
    }

    /**
     * @param array|string $definition
     * @param null $title
     * @return AbstractItem
     */
    public function build($definition, $title = null): AbstractItem
    {
        $menuItem = null;

        if (is_array($definition)) {
            $menuItem = new Group();

            foreach ($definition as $item) {
                $menuItem->addChild($this->build($item));
            }
        } else {
            $module = $this->admin->modules()->findModuleByControllerClass($definition);

            if (! $module) {
                throw new \DomainException(sprintf('No controller found for [%s] module ', $definition));
            }

            $menuItem = new Item($this->admin, $module);
        }

        $menuItem->setTitle($this->getMenuItemName($title ?: $definition));

        return $menuItem;
    }

    /**
     * @param array|string $definition
     * @return string
     */
    protected function getMenuItemName($definition): string
    {
        $name = is_array($definition) ? $definition[0] : $definition;
        $name = str_replace('Controller', '', class_basename($name));
        $name = snake_case($name);
        $key = 'arbory::modules.'.$name;

        $translated = trans($key);

        if ($translated === $key) {
            $generatedText = title_case(str_replace('_', ' ', $name));

            $this->translations->create([
                'locale' => \App::getLocale(),
                'namespace' => 'arbory',
                'group' => 'modules',
                'item' => $name,
                'text' => $generatedText,
            ]);

            return $generatedText;
        }

        return $translated;
    }
}
