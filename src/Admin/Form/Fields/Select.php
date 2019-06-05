<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\Fields\Concerns\HasSelectOptions;
use Arbory\Base\Admin\Form\Fields\Renderer\SelectFieldRenderer;

/**
 * Class Dropdown.
 */
class Select extends ControlField
{
    use HasSelectOptions;
    use HasRelationships;

    protected $control = \Arbory\Base\Admin\Form\Controls\SelectControl::class;

    protected $rendererClass = SelectFieldRenderer::class;

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var string
     */
    protected $optionTitleKey;

    /**
     * @param Request $request
     * @throws \RuntimeException
     */
    public function beforeModelSave(Request $request)
    {
        $property = $this->getName();
        $value = $request->has($this->getNameSpacedName())
            ? $request->input($this->getNameSpacedName())
            : null;

        if (! $this->containsValidValues($value)) {
            throw new \RuntimeException(sprintf('Bad select field value for "%s"', $this->getName()));
        }

        if (is_array($value)) {
            $value = implode(',', $value);
        }

        // Use relation foreign key when name matches relationships
        if ($this->isRelationship()) {
            $property = $this->getRelation()->getForeignKey();
        }

        $this->getModel()->setAttribute($property, $value);
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param bool $multiple
     * @return self
     */
    public function setMultiple(bool $multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @param mixed $input
     * @return bool
     */
    public function containsValidValues($input): bool
    {
        if (! is_array($input)) {
            $input = [$input];
        }

        foreach ($input as $item) {
            if (! empty($item) && ! $this->getOptions()->has($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array|mixed
     */
    public function getValue()
    {
        $value = parent::getValue();

        if ($this->isMultiple()) {
            if ($value instanceof Collection) {
                return $value->all();
            }

            if (is_string($value)) {
                $value = explode(',', $value);
            }

            return array_wrap($value);
        }

        return $value;
    }

    /**
     * @return Collection
     */
    public function getOptions(): Collection
    {
        if ($this->options === null && $this->isRelationship()) {
            return $this->getRelatedItems()->mapWithKeys(
                function (Model $model) {
                    $value = $this->getOptionTitleKey() ? $model->getAttribute($this->getOptionTitleKey()) : (string)
                    $model;

                    return [
                        $model->getKey() => $value,
                    ];
                }
            );
        }

        return $this->options;
    }

    /**
     * @return bool
     */
    public function isRelationship()
    {
        return method_exists($this->getModel(), $this->getName());
    }

    /**
     * @return string
     */
    public function getOptionTitleKey()
    {
        return $this->optionTitleKey;
    }

    /**
     * @param $optionTitleKey
     *
     * @return $this
     */
    public function setOptionTitleKey($optionTitleKey)
    {
        $this->optionTitleKey = $optionTitleKey;

        return $this;
    }
}
