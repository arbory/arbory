<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;

class ObjectRelationGroupedRenderer extends ObjectRelationRenderer
{
    /**
     * @return Element
     */
    public function render()
    {
        if ($this->field->hasIndentation()) {
            throw new \InvalidArgumentException('Field cannot be grouped and indented at the same time');
        }

        return parent::render()->addAttributes(['data-grouped' => $this->field->getGroupByAttribute()]);
    }

    /**
     * @return Element[]
     */
    protected function getAvailableRelationalItemsElement()
    {
        $items = [];
        $relationalGroups = $this->field->getOptions();

        foreach ($relationalGroups as $group) {
            foreach ($group as $relation) {
                $name = $this->getGroupName($relation);

                if (! array_key_exists($name, $items)) {
                    $items[$name] = Html::div(Html::strong($name)->addClass('title'))->addClass('group');
                }

                $element = $this->buildRelationalItemElement($relation, $this->field->hasRelationWith($relation));

                $items[$name]->append($element);
            }
        }

        return $items;
    }

    /**
     * @param Model $relation
     * @return string
     */
    private function getGroupName(Model $relation)
    {
        $attribute = $this->field->getGroupByAttribute();
        $getName = $this->field->getGroupByGetName();

        return $getName ? $getName($relation) : $relation->getAttribute($attribute);
    }
}
