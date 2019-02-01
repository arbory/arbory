<?php


namespace Arbory\Base\Admin\Form\Fields\Styles;


use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\FieldStyleInterface;
use Illuminate\Foundation\Application;

class StyleManager
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $styles;

    /**
     * @var string
     */
    protected $defaultStyle;

    /**
     * StyleManager constructor.
     *
     * @param Application $app
     * @param array       $styles
     * @param             $defaultStyle
     */
    public function __construct( Application $app, array $styles, $defaultStyle )
    {
        $this->app          = $app;
        $this->styles       = collect($styles);
        $this->defaultStyle = $defaultStyle;
    }

    /**
     * @param string $name
     * @param        $class
     *
     * @return $this
     */
    public function addStyle( string $name, $class )
    {
        $this->styles->put($name, $class);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function removeStyle( string $name )
    {
        $this->styles->forget($name);

        return $this;
    }

    /**
     * @param string         $name
     * @param FieldInterface $field
     *
     * @return mixed|null
     */
    public function make( string $name, FieldInterface $field )
    {
        if ( $this->styles->has($name) ) {
            /** @var FieldStyleInterface $style */
            $style = $this->app->make(
                $this->styles->get($name)
            );

            return $style->render($field);
        } else {
            throw new \InvalidArgumenCotException("Unknown field style '{$name}'");
        }
    }

    /**
     * @return string
     */
    public function getDefaultStyle(): string
    {
        return $this->defaultStyle;
    }

    /**
     * @param string $defaultStyle
     */
    public function setDefaultStyle( string $defaultStyle ): void
    {
        $this->defaultStyle = $defaultStyle;
    }
}