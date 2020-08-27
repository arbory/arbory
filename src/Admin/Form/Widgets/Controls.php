<?php

namespace Arbory\Base\Admin\Form\Widgets;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Illuminate\Contracts\Support\Renderable;

class Controls implements Renderable
{
    /**
     * @var Tools
     */
    protected $tools;
    /**
     * @var null
     */
    protected $backUrl;

    /**
     * Controls constructor.
     *
     * @param Tools $tools
     * @param null $backUrl
     */
    public function __construct(Tools $tools, $backUrl = null)
    {
        $this->tools = $tools;
        $this->backUrl = $backUrl;

        $this->compose();
    }

    public function compose()
    {
        $primary = $this->tools->getBlock('primary');
        $secondary = $this->tools->getBlock('secondary');

        $primary
            ->push(
                Button::create('save_and_return', true)
                    ->type('submit', 'secondary')
                    ->withIcon('check')
                    ->disableOnSubmit()
                    ->title(trans('arbory::resources.save_and_return'))
            )
            ->push(
                Button::create('save', true)
                    ->type('submit', 'primary')
                    ->withIcon('check')
                    ->disableOnSubmit()
                    ->title(trans('arbory::resources.save'))
            );

        if ($this->backUrl) {
            $secondary->push(
                Link::create($this->backUrl)
                    ->asButton('secondary')
                    ->withIcon('arrow_left')
                    ->title(trans('arbory::resources.back_to_list'))
            );
        }
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return Html::footer($this->tools)->addClass('main');
    }

    /**
     * @return Tools
     */
    public function getTools(): Tools
    {
        return $this->tools;
    }

    /**
     * @param Tools $tools
     *
     * @return Controls
     */
    public function setTools(Tools $tools): self
    {
        $this->tools = $tools;

        return $this;
    }
}
