<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Illuminate\View\View;

/**
 * Interface FieldInterface.
 */
interface FieldInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param $name string
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getNameSpacedName();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param $value string
     * @return $this
     */
    public function setValue($value);

    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @param $defaultValue string
     * @return $this
     */
    public function setDefaultValue($defaultValue);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param  string  $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * @return FieldSet
     */
    public function getFieldSet();

    /**
     * @return $this
     */
    public function setFieldSet(FieldSet $fieldSet);

    /**
     * @return Model
     */
    public function getModel();

    public function rules(string $rules): self;

    public function getRules(): array;

    public function beforeModelSave(Request $request);

    public function afterModelSave(Request $request);

    public function render(): mixed;

    public function getRendererClass(): ?string;

    public function setRendererClass(?string $renderable = null): self;

    public function getRenderer(): ?RendererInterface;

    /**
     * Set a render class override.
     */
    public function setRenderer(?RendererInterface $renderer): self;

    /**
     * @return string|null
     */
    public function getTooltip();

    /**
     * @param  string|null  $content
     */
    public function setTooltip($content = null): self;

    /**
     * @param  array  $breakpoints
     */
    public function setRows(int $rows, $breakpoints = []): self;

    /**
     * @return mixed
     */
    public function getRows();

    /**
     * @return string
     */
    public function getStyle();

    /**
     * @return mixed
     */
    public function setStyle(string $style): self;

    public function getFieldClasses(): array;

    /**
     * Element ID for label.
     *
     * @return string
     */
    public function getFieldId();

    /**
     * @return mixed
     */
    public function beforeRender(RendererInterface $renderer);

    public function isHidden(): bool;

    public function setHidden(bool $value): self;
}
