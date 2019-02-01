<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer;


use Arbory\Base\Admin\Form\Fields\ControlFieldInterface;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\FieldRenderOptionsInterface;
use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Elements\Inputs\Textarea;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

class ControlFieldRenderer implements Renderable
{
    /**
     * @var FieldInterface|ControlFieldInterface|FieldRenderOptionsInterface
     */
    protected $field;

    /**
     * ControlFieldRenderer constructor.
     *
     * @param ControlFieldInterface $field
     */
    public function __construct( ControlFieldInterface $field )
    {
        $this->field = $field;
    }

    public function render()
    {
        $element = $this->getElement();

        $element->addAttributes(
            $this->field->getAttributes()
        );

        if ( $this->field->getFieldId() ) {
            $element->addAttributes(
                [ 'id' => $this->field->getFieldId() ]
            );
        }

        $element->addClass(
            implode( " ", $this->field->getClasses() )
        );

        if($this->field->getName() &&
           !$element->attributes()->get('name')) {
            $element->setName($this->field->getNameSpacedName());
        }

        $element = $this->setValue($element);

        if($this->field instanceof ControlFieldInterface)
        {
            if($this->field->getDisabled()) {
                $element->addAttributes([
                    'disabled' => ''
                ]);
            }

            if($this->field->getReadOnly()) {
                $element->addAttributes([
                    'readonly' => ''
                ]);
            }
        }

        return $element;
    }

    /**
     * @return Input|\Arbory\Base\Html\Elements\Inputs\Select|Textarea
     */
    protected function getElement()
    {
        switch ( $this->field->getElementType() ) {
            case 'input':
                $element = Html::input();

                break;

            case 'textarea':
                $element = Html::textarea();

                break;
            case 'select':
                $element = Html::select();

                break;

            default:
                throw new \LogicException("Unknown element type");
        }

        return $element;
    }

    /**
     * @param $element
     *
     * @return mixed
     */
    protected function setValue($element)
    {
        if($value = $this->field->getValue()) {
            if($element instanceof Textarea) {
                $element->append($value);
            }

            if($element instanceof Input) {
                $element->setValue($value);
            }
        }

        return $element;
    }
}