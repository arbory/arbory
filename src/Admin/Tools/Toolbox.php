<?php

namespace Arbory\Base\Admin\Tools;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Toolbox.
 */
class Toolbox implements Renderable
{
    /**
     * Toolbox constructor.
     */
    public function __construct(protected ?string $url = null, protected ?ToolboxMenu $menu = null)
    {
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

    public function getMenu(): ?ToolboxMenu
    {
        return $this->menu;
    }

    public function setMenu(?ToolboxMenu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param $url
     * @return Toolbox
     */
    public static function create($url)
    {
        return new static($url);
    }
}
