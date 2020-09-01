<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Arbory\Base\Admin\Form\Fields\Concerns\IsControlField;
use Arbory\Base\Admin\Form\Fields\Concerns\IsTranslatable;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;

/**
 * Class AbstractField.
 */
abstract class AbstractField implements FieldInterface, ControlFieldInterface
{
    use IsTranslatable;
    use IsControlField;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * @var string
     */
    protected $rendererClass;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var mixed
     */
    protected $tooltip;

    /**
     * @var mixed
     */
    protected $rows;

    /**
     * @var string
     */
    protected $style;

    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * AbstractField constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameSpacedName()
    {
        return implode('.', [
            $this->getFieldSet()->getNamespace(),
            $this->getName(),
        ]);
    }

    /**
     * @return string
     */
    public function getFieldTypeName()
    {
        return 'type-'.camel_case(class_basename(static::class));
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if ($this->value === null) {
            $this->value = $this->getModel()->getAttribute($this->getName());
        }

        if ($this->hasNoValue() && $this->getDefaultValue()) {
            $this->value = $this->getDefaultValue();
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    private function hasNoValue()
    {
        return $this->value === null || $this->isEmptyCollection($this->value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function isEmptyCollection($value)
    {
        return $value instanceof Collection && $value->isEmpty();
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return $this
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if ($this->label === null) {
            return $this->name;
        }

        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->getFieldSet()->getModel();
    }

    /**
     * @return FieldSet
     */
    public function getFieldSet()
    {
        return $this->fieldSet;
    }

    /**
     * @param FieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet(FieldSet $fieldSet)
    {
        $this->fieldSet = $fieldSet;

        return $this;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave(Request $request)
    {
        if ($this->isDisabled()) {
            return;
        }

        $value = $request->has($this->getNameSpacedName())
            ? $request->input($this->getNameSpacedName())
            : null;

        $this->getModel()->setAttribute($this->getName(), $value);
    }

    /**
     * @param string $rules
     * @return FieldInterface
     */
    public function rules(string $rules): FieldInterface
    {
        $this->rules = array_merge($this->rules, explode('|', $rules));

        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [$this->getNameSpacedName() => implode('|', $this->rules)];
    }

    /**
     * @param Request $request
     */
    public function afterModelSave(Request $request)
    {
    }

    /**
     * @return View
     */
    public function render()
    {
        return $this->getRenderer()->render();
    }

    /**
     * @return string|null
     */
    public function getRendererClass(): ?string
    {
        return $this->rendererClass;
    }

    /**
     * @param string|null $rendererClass
     *
     * @return FieldInterface
     */
    public function setRendererClass(?string $rendererClass = null): FieldInterface
    {
        $this->rendererClass = $rendererClass;

        $this->setRenderer(null);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param string|null $content
     *
     * @return FieldInterface
     */
    public function setTooltip($content = null): FieldInterface
    {
        $this->tooltip = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     * @param array $breakpoints
     *
     * @return FieldInterface
     */
    public function setRows(int $rows, $breakpoints = []): FieldInterface
    {
        $this->rows = ['size' => $rows, 'breakpoints' => $breakpoints];

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string $style
     *
     * @return FieldInterface
     */
    public function setStyle(string $style): FieldInterface
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return array
     */
    public function getFieldClasses(): array
    {
        $type = snake_case(class_basename(get_class($this)), '-');

        return ["type-{$type}"];
    }

    /**
     * @return string|null
     */
    public function getFieldId()
    {
    }

    /**
     * @return RendererInterface|null
     */
    public function getRenderer(): ?RendererInterface
    {
        if (is_null($this->renderer) && $this->rendererClass) {
            $this->renderer = $this->newRenderer();
        }

        return $this->renderer;
    }

    /**
     * @param RendererInterface|null $renderer
     *
     * @return FieldInterface
     */
    public function setRenderer(?RendererInterface $renderer): FieldInterface
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return RendererInterface
     */
    public function newRenderer()
    {
        return app()->makeWith(
            $this->rendererClass,
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @param RendererInterface $renderer
     *
     * @return mixed|void
     */
    public function beforeRender(RendererInterface $renderer)
    {
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     *
     * @return FieldInterface
     */
    public function setHidden(bool $hidden): FieldInterface
    {
        $this->hidden = $hidden;

        return $this;
    }
}
