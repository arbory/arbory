<?php

namespace Arbory\Base\Admin\Traits;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Module;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

trait BulkEdit
{

    /**
     * @param Form $form
     * @return Form
     */
    protected function bulkEditForm(Form $form): Form
    {
        return $form;
    }

    /**
     * @param Model $model
     * @param bool $ids
     * @param bool $withChecks
     * @return Form
     */
    protected function buildBulkEditForm(Model $model): Form
    {
        $ids = request('ids');
        $form = new Form($model);
        $form->setAction($this->url('bulkupdate', $model->getKey()));
        $form->setModule($this->module());
        $form->setRenderer(new Form\BulkEditFormBuilder($form));
        if ($ids) {
            $form->title(trans('arbory::resources.bulk_edit_form_title', ['count' => count($ids)]));
            $form->setFields(function (Form\FieldSet $fieldSet) use ($ids) {
                $fieldSet->hidden('ids')->setValue(implode(',', $ids));
            });
        }
        $this->bulkEditForm($form);

        if ($ids) {
            $this->addCheckboxesToEachInput($form);
        } else {
            $this->preprocessBulkUpdate($form);
        }

        return $form;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function confirmBulkEditDialog(Request $request): View
    {
        if (!$request->has('ids')) {
            return view('arbory::dialogs.form_mass_empty');
        }
        return view('arbory::dialogs.form_mass_edit', [
            'formTarget' => $this->url('index'),
            'objectName' => $this->resource,
            'form' => $this->buildBulkEditForm($this->resource())->render(),
            'ids' => ''
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bulkUpdate(Request $request)
    {
        $ids = $request->input('resource.ids');

        foreach (explode(',', $ids) as $id) {
            $resource = $this->resource()->find($id);
            $form = $this->buildBulkEditForm($resource);
            $form->update($request);
        }

        return $this->getAfterBulkEditResponse($request);
    }

    /**
     * @param Form $form
     */
    protected function addCheckboxesToEachInput(Form $form)
    {
        $fieldSet = $form->fields();
        $items = $fieldSet->getFields();

        //count when iput added only, cache adjunctions in collection
        $counter = 0;

        foreach ($fieldSet->getIterator() as $key => $field) {
            if ($field->getName() == 'ids')
                continue;

            $checkbox = $this->getInputControlCheckbox($field->getName(), $field->getLabel());

            $checkbox->setFieldSet($fieldSet);

            $fieldSet->offsetGet($key + $counter)
                ->addAttributes(['disabled' => 'disabled']);
            //Add ckeckbox before input
            $items->splice($key + $counter, 0, [$checkbox]);
            $counter++;
        };
    }

    /**
     * @param $fieldName
     * @param $fieldLabel
     * @return Form\Fields\Checkbox
     */
    protected function getInputControlCheckbox($fieldName, $fieldLabel): Form\Fields\Checkbox
    {
        $checkbox = new Form\Fields\Checkbox($fieldName . '_control');
        $checkbox->addAttributes(['data-target' => $fieldName])
            ->addClass('bulk-control')
            ->setLabel(trans('arbory::resources.check_to_change', ['input' => $fieldLabel]));

        return $checkbox;
    }

    /**
     * @param $form
     */
    protected function preprocessBulkUpdate(Form $form)
    {
        //change original
        $fieldSet = $form->fields();
        $items = $fieldSet->getFields();

        foreach ($fieldSet->getIterator() as $key => $field) {
            $name = $field->getName();
            $nameSpace = $form->getNamespace();
            $fieldName = $nameSpace . '.' . $name . '_control';
            if ($field->getName() != 'ids' &&
                !request()->has($fieldName)) {
                $items->forget($key);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function getAfterBulkEditResponse(Request $request)
    {
        return redirect($this->module()->url('index'));
    }
}
