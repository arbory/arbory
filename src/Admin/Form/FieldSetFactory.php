<?php

namespace Arbory\Base\Admin\Form;

use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;

class FieldSetFactory
{
    /**
     * @var StyleManager
     */
    protected $styleManager;

    /**
     * FieldSetFactory constructor.
     *
     * @param StyleManager $styleManager
     */
    public function __construct(StyleManager $styleManager)
    {
        $this->styleManager = $styleManager;
    }

    /**
     * @param Model $model
     * @param string|null $namespace
     * @param string|null $defaultStyle
     *
     * @return FieldSet
     */
    public function make($model, $namespace = null, $defaultStyle = null)
    {
        $fieldSet = $this->newFieldSet($model, $namespace);

        $fieldSet->setStyleManager($this->styleManager);
        $fieldSet->setDefaultStyle($defaultStyle ?: $this->styleManager->getDefaultStyle());

        return $fieldSet;
    }

    protected function newFieldSet($model, $namespace)
    {
        return new FieldSet($model, $namespace);
    }
}
