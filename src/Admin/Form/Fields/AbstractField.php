<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\IsControlField;
use Arbory\Base\Admin\Form\Fields\Concerns\IsTranslatable;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    protected string $name;

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
    protected string $label;

    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * @var string
     */
    protected string $rendererClass = '';

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
    protected string $style = '';

    /**
     * @var bool
     */
    protected bool $hidden = false;

    /**
     * AbstractField constructor.
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameSpacedName(): string
    {
        return implode('.', [
            $this->getFieldSet()->getNamespace(),
            $this->getName(),
        ]);
    }

    /**
     * @return string
     */
    public function getFieldTypeName(): string
    {
        return 'type-' . Str::camel(class_basename(static::class));
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
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
    private function hasNoValue(): bool
    {
        return $this->value === null || $this->isEmptyCollection($this->value);
    }

    /**
     * @return bool
     */
    private function isEmptyCollection(mixed $value): bool
    {
        return $value instanceof Collection && $value->isEmpty();
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return $this
     */
    public function setDefaultValue($defaultValue): self
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label ?? $this->name;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->getFieldSet()->getModel();
    }

    /**
     * @return FieldSet
     */
    public function getFieldSet(): FieldSet
    {
        return $this->fieldSet;
    }

    /**
     * @return $this
     */
    public function setFieldSet(FieldSet $fieldSet): self
    {
        $this->fieldSet = $fieldSet;

        return $this;
    }

    public function beforeModelSave(Request $request): void
    {
        if ($this->isDisabled()) {
            return;
        }

        $value = $request->has($this->getNameSpacedName())
            ? $request->input($this->getNameSpacedName())
            : null;

        $this->getModel()->setAttribute($this->getName(), $value);
    }

    public function rules(string $rules): FieldInterface
    {
        $this->rules = array_merge($this->rules, explode('|', $rules));

        return $this;
    }

    public function getRules(): array
    {
        return [$this->getNameSpacedName() => implode('|', $this->rules)];
    }

    public function afterModelSave(Request $request)
    {
    }

    public function render(): mixed
    {
        return $this->getRenderer()->render();
    }

    public function getRendererClass(): ?string
    {
        return $this->rendererClass;
    }

    public function setRendererClass(?string $rendererClass = null): FieldInterface
    {
        $this->rendererClass = $rendererClass;

        $this->setRenderer(null);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTooltip(): ?string
    {
        return $this->tooltip;
    }

    /**
     * @param string|null $content
     */
    public function setTooltip($content = null): FieldInterface
    {
        $this->tooltip = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRows(): mixed
    {
        return $this->rows;
    }

    /**
     * @param array $breakpoints
     */
    public function setRows(int $rows, $breakpoints = []): FieldInterface
    {
        $this->rows = ['size' => $rows, 'breakpoints' => $breakpoints];

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): FieldInterface
    {
        $this->style = $style;

        return $this;
    }

    public function getFieldClasses(): array
    {
        $type = Str::snake(class_basename($this::class), '-');

        return ["type-{$type}"];
    }

    /**
     * @return string|null
     */
    public function getFieldId()
    {
    }

    public function getRenderer(): ?RendererInterface
    {
        if (is_null($this->renderer) && $this->rendererClass) {
            $this->renderer = $this->newRenderer();
        }

        return $this->renderer;
    }

    public function setRenderer(?RendererInterface $renderer): FieldInterface
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return RendererInterface
     */
    public function newRenderer(): RendererInterface
    {
        return app()->makeWith(
            $this->rendererClass,
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @return mixed|void
     */
    public function beforeRender(RendererInterface $renderer)
    {
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): FieldInterface
    {
        $this->hidden = $hidden;

        return $this;
    }
}
