<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Html;
use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;

/**
 * HasOne for json data fields.
 *
 * Class VirtualHasOne
 */
class VirtualHasOne extends HasOne
{
    /**
     * @return mixed
     */
    public function getValue()
    {
        $value = parent::getValue();

        return array_wrap($value);
    }

    /**
     * @return Element
     */
    public function render()
    {
        $item = $this->getValue();
        $model = $this->getModel();

        $block = Html::div()
                     ->addClass('section content-fields')
                     ->addAttributes($this->getAttributes())
                     ->addClass(implode(' ', $this->getClasses()));

        $fieldSet = $this->getRelationFieldSet($model);

        foreach ($fieldSet->getFields() as $field) {
            $field->setValue(
                array_get($item, $field->getName())
            );
        }

        $block->append(
            $fieldSet->render()
        );

        return $block;
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function afterModelSave(Request $request)
    {
        $model = $this->getModel();

        $value = (array) $request->input($this->getNameSpacedName(), []);

        $data = [];

        foreach ($this->getRelationFieldSet($model)->getFields() as $field) {
            $name = $field->getName();

            $data[$name] = array_get($value, $name);
        }

        $model->setAttribute($this->getName(), $data);
        $model->save();
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $rules = [];

        $relatedModel = $this->getModel();

        foreach ($this->getRelationFieldSet($relatedModel)->getFields() as $field) {
            $rules = array_merge($rules, $field->getRules());
        }

        return $rules;
    }

    /**
     * @param Model $relatedModel
     *
     * @return FieldSet
     */
    public function getRelationFieldSet(Model $relatedModel)
    {
        $fieldSet = $this->getNestedFieldSet($relatedModel);

        return $fieldSet;
    }
}
