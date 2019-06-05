<?php

namespace Arbory\Base\Admin;

use Closure;
use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Admin\Layout\PageInterface;

class Page extends Layout implements PageInterface
{
    const SLOTS = [
        'header_right',
    ];

    protected $view = 'arbory::controllers.resource.layout';

    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    public function __construct(?Closure $callback = null)
    {
        parent::__construct($callback);

        $this->breadcrumbs = new Breadcrumbs();
    }

    /**
     * @param string $title
     * @param string $url
     *
     * @return $this
     */
    public function addBreadcrumb($title, $url)
    {
        $this->breadcrumbs->addItem($title, $url);

        return $this;
    }

    /**
     * @return Breadcrumbs
     */
    public function getBreadcrumbs(): Breadcrumbs
    {
        return $this->breadcrumbs;
    }

    /**
     * @param Breadcrumbs|null $breadcrumbs
     *
     * @return Page
     */
    public function setBreadcrumbs(?Breadcrumbs $breadcrumbs): PageInterface
    {
        $this->breadcrumbs = $breadcrumbs;

        return $this;
    }

    /**
     * @param mixed $content
     *
     * @return string
     * @throws \Throwable
     */
    public function contents($content)
    {
        $content = new Content(
            [
                $this->header(),
                $content,
            ]
        );

        $variables = [
            'content' => $content,
            'bodyClass' => $this->bodyClass,
        ];

        return view($this->view, $variables)->render();
    }

    protected function header()
    {
        return Html::header([
            $this->getBreadcrumbs(),
            $this->slot('header_right'),
            $this->slot('header_right_filter'),
        ]);
    }

    /**
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * @param string $view
     *
     * @return Page
     */
    public function setView(string $view): self
    {
        $this->view = $view;

        return $this;
    }
}
