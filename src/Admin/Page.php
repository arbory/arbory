<?php


namespace Arbory\Base\Admin;


use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Closure;

class Page extends Layout
{

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
     * @param Breadcrumbs $breadcrumbs
     *
     * @return Page
     */
    public function setBreadcrumbs(Breadcrumbs $breadcrumbs): Page
    {
        $this->breadcrumbs = $breadcrumbs;

        return $this;
    }

    /**
     * @param mixed                $content
     * @param LayoutInterface|null $root
     *
     * @return string
     * @throws \Throwable
     */
    public function contents($content)
    {
        $content = new Content(
            [
                $this->header(),
                $content
            ]
        );

        $variables = [
            'content'   => $content,
            'bodyClass' => $this->bodyClass,
        ];

        return view('arbory::controllers.resource.layout', $variables)->render();
    }

    protected function header()
    {
        return Html::header([
            $this->getBreadcrumbs(),
            $this->slot('header_right')
        ]);
    }

}