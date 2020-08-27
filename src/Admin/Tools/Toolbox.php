<?php

namespace Arbory\Base\Admin\Tools;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Toolbox.
 */
class Toolbox implements Renderable
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var ToolboxMenu
     */
    protected $menu;

    /**
     * Toolbox constructor.
     *
     * @param string $url
     * @param ToolboxMenu|null $menu
     */
    public function __construct($url = null, ToolboxMenu $menu = null)
    {
        $this->url = $url;
        $this->menu = $menu;
    }

    /**
     * @return Element
     */
    public function render()
    {
        if (! $this->url && ! $this->menu) {
            return;
        }

        $attributes = [];

        if ($this->url) {
            $attributes['data-url'] = $this->url;
        }

        return Html::div(
            Html::div([
                Html::button(Html::i('settings')->addClass('mt-icon'))
                    ->addClass('button trigger only-icon')
                    ->addAttributes(['type' => 'button']),
                Html::menu([
                    Html::ul($this->menu),
                ])
                    ->addClass('toolbox-items')
                    ->addAttributes(['type' => 'toolbar']),
            ])
                ->addClass('toolbox')
                ->addAttributes($attributes)
        )->addClass('only-icon toolbox-cell');
    }

    /**
     * @return ToolboxMenu|null
     */
    public function getMenu(): ?ToolboxMenu
    {
        return $this->menu;
    }

    /**
     * @param ToolboxMenu|null $menu
     *
     * @return Toolbox
     */
    public function setMenu(?ToolboxMenu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Toolbox
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param $url
     *
     * @return Toolbox
     */
    public static function create($url)
    {
        return new static($url);
    }
}
