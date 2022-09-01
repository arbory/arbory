<?php

namespace Arbory\Base\Menu;

use DomainException;
use App;
use Arbory\Base\Admin\Admin;
use Illuminate\Support\Str;
use Waavi\Translation\Repositories\TranslationRepository;

class MenuItemFactory
{
    public function __construct(protected Admin $admin, protected TranslationRepository $translations)
    {
    }

    /**
     * @param  null  $title
     */
    public function build(array|string $definition, $title = null): AbstractItem
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
                throw new DomainException(sprintf('No controller found for [%s] module ', $definition));
            }

            $menuItem = new Item($this->admin, $module);
        }

        $menuItem->setTitle($this->getMenuItemName($title ?: $definition));

        return $menuItem;
    }

    protected function getMenuItemName(array|string $definition): string
    {
        $name = is_array($definition) ? $definition[0] : $definition;
        $name = str_replace('Controller', '', class_basename($name));
        $name = Str::snake($name);
        $key = 'arbory::modules.'.$name;

        $translated = trans($key);

        if ($translated === $key) {
            $generatedText = Str::title(str_replace('_', ' ', $name));

            $this->translations->create([
                'locale' => App::getLocale(),
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
