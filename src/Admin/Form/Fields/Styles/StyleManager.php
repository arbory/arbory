<?php

namespace Arbory\Base\Admin\Form\Fields\Styles;

use Illuminate\Foundation\Application;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\GenericRenderer;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\FieldStyleInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptions;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class StyleManager
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $styles;

    /**
     * StyleManager constructor.
     *
     * @param  $defaultStyle
     * @param string $defaultStyle
     */
    public function __construct(protected Application $app, array $styles, protected $defaultStyle)
    {
        $this->styles = collect($styles);
    }

    /**
     * @param  $class
     * @return $this
     */
    public function addStyle(string $name, $class)
    {
        $this->styles->put($name, $class);

        return $this;
    }

    /**
     * @return $this
     */
    public function removeStyle(string $name)
    {
        $this->styles->forget($name);

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function render(string $name, FieldInterface $field, ?StyleOptionsInterface $options = null)
    {
        if ($this->styles->has($name)) {
            /** @var FieldStyleInterface $style */
            $style = $this->app->make(
                $this->styles->get($name)
            );

            $options = $options ?: $this->newOptions();

            if ($renderer = $field->getRenderer()) {
                $options = $field->getRenderer()->configure($options);
            } else {
                $renderer = new GenericRenderer();

                $renderer->setField($field);
            }

            $field->beforeRender($renderer);

            return $style->render($renderer, $options);
        } else {
            throw new \InvalidArgumentException("Unknown field style '{$name}'");
        }
    }

    public function getDefaultStyle(): string
    {
        return $this->defaultStyle;
    }

    public function setDefaultStyle(string $defaultStyle): void
    {
        $this->defaultStyle = $defaultStyle;
    }

    public function newOptions()
    {
        return new StyleOptions();
    }
}
