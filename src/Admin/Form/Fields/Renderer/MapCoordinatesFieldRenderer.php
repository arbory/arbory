<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Admin\Form\Fields\FieldRenderOptionsInterface;
use Arbory\Base\Admin\Form\Fields\Hidden;
use Arbory\Base\Admin\Form\Fields\MapCoordinates;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

class MapCoordinatesFieldRenderer implements Renderable
{
    /**
     * @var MapCoordinates
     */
    protected $field;

    /**
     * @param MapCoordinates $field
     */
    public function __construct( MapCoordinates $field )
    {
        $this->field = $field;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $value = $this->field->getValue();
        $body  = Html::div();

        $body->append(Html::div()->addClass('canvas'));

        $body->append(
            $this->field->getNestedFieldSet($this->field->getModel())->render()
        );

        return $body->addClass('body');
    }
}
