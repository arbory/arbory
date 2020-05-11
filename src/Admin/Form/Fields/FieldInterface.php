<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;

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
     * @param string $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * @return FieldSet
     */
    public function getFieldSet();

    /**
     * @param FieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet(FieldSet $fieldSet);

    /**
     * @return Model
     */
    public function getModel();

    /**
     * @param string $rules
     * @return FieldInterface
     */
    public function rules(string $rules): self;

    /**
     * @return array
     */
    public function getRules(): array;

    /**
     * @param Request $request
     */
    public function beforeModelSave(Request $request);

    /**
     * @param Request $request
     */
    public function afterModelSave(Request $request);

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Element|string
     */
    public function render();

    /**
     * @return string|null
     */
    public function getRendererClass(): ?string;

    /**
     * @param string|null $renderable
     *
     * @return FieldInterface
     */
    public function setRendererClass(?string $renderable = null): self;

    /**
     * @return RendererInterface|null
     */
    public function getRenderer(): ?RendererInterface;

    /**
     * Set a render class override.
     *
     * @param RendererInterface|null $renderer
     *
     * @return FieldInterface
     */
    public function setRenderer(?RendererInterface $renderer): self;

    /**
     * @return string|null
     */
    public function getTooltip();

    /**
     * @param string|null $content
     *
     * @return FieldInterface
     */
    public function setTooltip($content = null): self;

    /**
     * @param int $rows
     * @param array $breakpoints
     *
     * @return FieldInterface
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
     * @param string $style
     *
     * @return mixed
     */
    public function setStyle(string $style): self;

    /**
     * @return array
     */
    public function getFieldClasses(): array;

    /**
     * Element ID for label.
     *
     * @return string
     */
    public function getFieldId();

    /**
     * @param RendererInterface $renderer
     *
     * @return mixed
     */
    public function beforeRender(RendererInterface $renderer);

    /**
     * @return bool
     */
    public function isHidden(): bool;

    /**
     * @param bool $value
     *
     * @return FieldInterface
     */
    public function setHidden(bool $value): self;
}
