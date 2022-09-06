<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Module;
use Arbory\Base\Admin\Module\ResourceRoutes;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use InvalidArgumentException;
use ReflectionClass;
use Route;

class Item extends AbstractItem
{
    public function __construct(protected Admin $admin, protected Module $module)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function render(Element $parentElement): Element
    {
        return
            $parentElement->append(
                Html::link([
                    Html::abbr($this->getAbbreviation())->addAttributes(['title' => $this->getTitle()]),
                    Html::span($this->getTitle())->addClass('name'),
                ])
                    ->addClass('trigger ' . ($this->isActive() ? 'active' : ''))
                    ->addAttributes(['href' => $this->getUrl()])
            );
    }

    public function getRoute(): ResourceRoutes
    {
        return $this->admin->routes()->findByModule($this->getModule());
    }

    /**
     * @return string
     *
     * @throws InvalidArgumentException
     */
    protected function getUrl()
    {
        return $this->getRoute()->getUrl('index');
    }

    public function isActive(): bool
    {
        $currentController = (new ReflectionClass(Route::getCurrentRoute()->getController()))->getName();

        return $currentController === $this->module->getControllerClass();
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function setModule(Module $module)
    {
        $this->module = $module;
    }

    public function isAccessible(): bool
    {
        return $this->module->isAuthorized();
    }
}
