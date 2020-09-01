<?php

namespace Arbory\Base\Admin\Form;

use Closure;
use Arbory\Base\Admin\Layout\Grid;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Layout\Grid\Column;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;

class FieldSetRenderer implements FieldSetRendererInterface
{
    /**
     * @var string|null
     */
    protected $defaultStyle;

    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * @var StyleManager
     */
    protected $styleManager;

    /**
     * FieldSetRenderer constructor.
     *
     * @param FieldSet $fieldSet
     * @param StyleManager $styleManager
     */
    public function __construct(FieldSet $fieldSet, StyleManager $styleManager)
    {
        $this->fieldSet = $fieldSet;
        $this->styleManager = $styleManager;
    }

    /**
     * @return string|null
     */
    public function getDefaultStyle(): ?string
    {
        return $this->fieldSet->getDefaultStyle();
    }

    /**
     * @param string $value
     *
     * @return FieldSetRendererInterface
     */
    public function setDefaultStyle(string $value): FieldSetRendererInterface
    {
        $this->defaultStyle = $value;

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $collection = $this->fieldSet->getFields();
        $grid = new Grid();
        $columns = 0;

        $hasRows = $collection->filter(function ($field) {
            return $field->getRows();
        })->count();

        if (! $hasRows) {
            return new Content(
                $collection
                    ->map(Closure::fromCallable([$this, 'renderField']))
            );
        }

        $currentRow = $grid->row();

        foreach ($this->fieldSet->all() as $field) {
            if ($field->isHidden()) {
                continue;
            }

            $rendered = $this->renderField($field);

            if (blank($rendered)) {
                continue;
            }

            $rows = $field->getRows() ?? [
                'size' => $grid->getRowSize(),
                'breakpoints' => [],
            ];

            $columnsExpected = $columns + $rows['size'];

            if ($columnsExpected > $grid->getRowSize()) {
                $currentRow = $grid->row();

                $columns = $rows['size'];
            } else {
                $columns = $columnsExpected;
            }

            $currentRow->addColumn($this->createColumn($rows, $rendered));
        }

        return $grid;
    }

    /**
     * @param FieldInterface $field
     *
     * @return mixed|null
     */
    protected function renderField(FieldInterface $field)
    {
        if ($field->isHidden()) {
            return;
        }

        $style = $field->getStyle() ?: $this->getDefaultStyle();

        $rendered = $this->styleManager->render($style, $field);

        return $rendered;
    }

    /**
     * @param array $rows
     * @param       $content
     *
     * @return Column
     */
    protected function createColumn(array $rows, $content)
    {
        $column = new Column($rows['size'], $content);

        $column->breakpoints(array_merge(
            [Column::BREAKPOINT_DEFAULT => $rows['size']],
            $rows['breakpoints']
        ));

        return $column;
    }
}
