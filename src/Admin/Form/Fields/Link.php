<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Form\Fields\Concerns\HasNestedFieldSet;

class Link extends HasOne
{
    use HasNestedFieldSet;

    protected $style = 'nested';

    protected $urlRules;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->wrapper = function ($content) {
            $div = Html::div()->addClass('link-body');
            $fieldset = Html::fieldset()->addClass('item');

            $div->append($fieldset);
            $fieldset->append($content);

            return $div;
        };

        parent::__construct($name, null);
    }

    public function configureFieldSet(FieldSet $fieldSet)
    {
        $fieldSet->text('href');
        $fieldSet->text('title');
        $fieldSet->checkbox('new_tab');

        $fieldSet
            ->getFields()
            ->each(function (FieldInterface $field) {
                if ($field instanceof ControlFieldInterface) {
                    $field->setInteractive($this->isInteractive());
                    $field->setDisabled($this->isDisabled());
                }
            });

        return $fieldSet;
    }

    /**
     * @return mixed
     */
    public function getUrlRules()
    {
        return $this->urlRules;
    }

    /**
     * @param mixed $urlRules
     */
    public function setUrlRules($urlRules): void
    {
        $this->urlRules = $urlRules;
    }
}
