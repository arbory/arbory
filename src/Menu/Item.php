<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Module;
use Arbory\Base\Html\Elements;
use Arbory\Base\Admin\Module\ResourceRoutes;

class Item extends AbstractItem
{
    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @var \Arbory\Base\Admin\Module
     */
    protected $module;

    public function __construct(
        Admin $admin,
        Module $module
    ) {
        $this->admin = $admin;
        $this->module = $module;
    }

    /**
     * @param Elements\Element $parentElement
     * @return Elements\Element
     * @throws \InvalidArgumentException
     */
    public function render(Elements\Element $parentElement): Elements\Element
    {
        return
            $parentElement->append(
                Html::link([
                    Html::abbr($this->getAbbreviation())->addAttributes(['title' => $this->getTitle()]),
                    Html::span($this->getTitle())->addClass('name'),
                ])
                    ->addClass('trigger '.($this->isActive() ? 'active' : ''))
                    ->addAttributes(['href' => $this->getUrl()])
            );
    }

    /**
     * @return ResourceRoutes
     */
    public function getRoute(): ResourceRoutes
    {
        return $this->admin->routes()->findByModule($this->getModule());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getUrl()
    {
        return $this->getRoute()->getUrl('index');
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $currentController = (new \ReflectionClass(\Route::getCurrentRoute()->getController()))->getName();

        return $currentController === $this->module->getControllerClass();
    }

    /**
     * @return \Arbory\Base\Admin\Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }

    /**
     * @param \Arbory\Base\Admin\Module $module
     */
    public function setModule(Module $module)
    {
        $this->module = $module;
    }

    /**
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->module->isAuthorized();
    }
}
