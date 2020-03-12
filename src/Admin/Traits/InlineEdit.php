<?php

namespace Arbory\Base\Admin\Traits;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Grid\Column;
use Arbory\Base\Http\Requests\InlineEditRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * Trait InlineEdit.
 */
trait InlineEdit
{
    /**
     * @var string
     */
    protected $inlineFormStyle = 'basic';

    /**
     * @param InlineEditRequest|Request $request
     * @param mixed $resourceId
     * @return JsonResponse
     */
    public function inlineEdit(InlineEditRequest $request, $resourceId): JsonResponse
    {
        $fieldName = $request->get('column');

        $resource = $this->findOrNew($resourceId);
        $form = $this->buildInlineForm($resource, $fieldName);

        return response()->json([
            'field' => (string) $form->fields()->render()
        ]);
    }

    /**
     * @param InlineEditRequest|Request $request
     * @param mixed  $resourceId
     * @return JsonResponse
     */
    public function inlineUpdate(InlineEditRequest $request, $resourceId): JsonResponse
    {
        $fieldName = $request->get('column');

        $resource = $this->findOrNew($resourceId);
        $form = $this->buildInlineForm($resource, $fieldName);

        $form->validate();
        $form->update($request);

        return response()->json([
            'columnValue' => $this->getInlineUpdateColumnValue($resourceId, $fieldName)
        ]);
    }

    /**
     * @param FieldSet $fields
     * @return FieldSet
     */
    protected function inlineFormFields(FieldSet $fields): FieldSet
    {
        return $fields;
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function inlineForm(Form $form): Form
    {
        return $form;
    }

    /**
     * @param Model $model
     * @param string $fieldName
     * @return Form
     */
    protected function buildInlineForm(Model $model, string $fieldName): Form
    {
        $field = $this->findInlineFormField($model, $fieldName);

        $form = new Form($model);
        $form->setModule($this->module());
        $form->fields()->setDefaultStyle($this->getInlineFormStyle());
        $form->setFields(function (FieldSet $fields) use ($field) {
            $fields->add($field);
        });

        return $this->inlineForm($form) ?: $form;
    }

    /**
     * @param Model $model
     * @param string $fieldName
     * @return Form\Fields\AbstractField|null
     */
    protected function findInlineFormField(Model $model, string $fieldName)
    {
        $fieldSet = new FieldSet($model, null);
        $field = $this->inlineFormFields($fieldSet)->getFieldByName($fieldName);

        if (! $field) {
            throw new InvalidArgumentException(sprintf('Inline form field with name "%s" not found!', $fieldName));
        }

        return $field;
    }

    /**
     * @return string
     */
    protected function getInlineFormStyle(): string
    {
        return $this->inlineFormStyle;
    }

    /**
     * @param mixed $resourceId
     * @param string $columnName
     * @return string
     */
    protected function getInlineUpdateColumnValue($resourceId, string $columnName): string
    {
        $resource = $this->findOrNew($resourceId);
        $grid = $this->buildGrid($resource);

        return $grid->getColumns()
            ->first(function (Column $column) use ($columnName) {
                return $column->getName() === $columnName;
            })
            ->callDisplayCallback($resource);
    }

    /**
     * @param mixed $resourceId
     * @return Model
     */
    abstract protected function findOrNew(Model $model): Model;

    /**
     * @param Model $model
     * @return Grid
     */
    abstract protected function buildGrid(Model $model);
}
