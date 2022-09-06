<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Controls\CheckboxControl;
use Arbory\Base\Admin\Form\Fields\ControlFieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

/**
 * Class AssociatedSetRenderer.
 */
class AssociatedSetRenderer extends ControlFieldRenderer
{
    /**
     * @var array
     */
    protected array $values;

    /**
     * AssociatedSetRenderer constructor.
     */
    public function __construct(protected ControlFieldInterface $field)
    {
        $this->values = (array) $field->getValue();

        parent::__construct($this->field);
    }

    /**
     * @return Content
     */
    protected function getAssociatedItemsList()
    {
        $content = new Content();

        $index = 0;

        foreach ($this->field->getOptions() as $value => $label) {
            $content[] = $this->getAssociatedItem(
                $this->field->getNameSpacedName() . '.' . $index,
                $value,
                $label
            );

            $index++;
        }

        return $content;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $label
     * @return Element
     */
    protected function getAssociatedItem($name, $value, $label)
    {
        $checkbox = new CheckboxControl();

        $inputName = Element::formatName($name);

        $checkbox->setName($inputName);
        $checkbox->setValue($value);

        if ($this->field instanceof ControlFieldInterface) {
            $checkbox->setReadOnly(! $this->field->isInteractive());
            $checkbox->setDisabled($this->field->isDisabled());
        }

        if (in_array($value, $this->values, true)) {
            $checkbox->setChecked(true);
        }

        return Html::div([
            $checkbox->render($checkbox->element()),
            Html::label((string) $label)->addAttributes([
                'for' => $checkbox->getAttributes()['id'] ?? $this->field->getFieldId(),
            ]),
        ])
            ->addClass('type-associated-set-item');
    }

    public function render(): Content
    {
        return $this->getAssociatedItemsList();
    }

    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        $options->addClass('type-associated-set');

        return $options;
    }
}
