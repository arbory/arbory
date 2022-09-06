<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;
use Arbory\Base\Admin\Layout\Grid;
use Arbory\Base\Admin\Layout\Grid\Column;
use Arbory\Base\Html\Elements\Content;
use Closure;

class FieldSetRenderer implements FieldSetRendererInterface
{
    /**
     * @var string|null
     */
    protected $defaultStyle;

    /**
     * FieldSetRenderer constructor.
     */
    public function __construct(protected FieldSet $fieldSet, protected StyleManager $styleManager)
    {
    }

    public function getDefaultStyle(): ?string
    {
        return $this->fieldSet->getDefaultStyle();
    }

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

        $hasRows = $collection->filter(fn ($field) => $field->getRows())->count();

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
     * @return mixed|null
     */
    protected function renderField(FieldInterface $field)
    {
        if ($field->isHidden()) {
            return;
        }

        $style = $field->getStyle() ?: $this->getDefaultStyle();

        return $this->styleManager->render($style, $field);
    }

    /**
     * @param  $content
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
