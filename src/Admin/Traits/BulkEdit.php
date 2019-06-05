<?php

namespace Arbory\Base\Admin\Traits;

use Illuminate\View\View;
use Arbory\Base\Admin\Form;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

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
     * @return Form
     */
    protected function buildBulkEditForm(Model $model): Form
    {
        $form = new Form($model);
        $form->setAction($this->url('bulkupdate', $model->getKey()));
        $form->setModule($this->module());
        $form->setRenderer(new Form\BulkEditFormBuilder($form));
        $this->bulkEditForm($form);

        return $form;
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function additionalFormControls(Form $form): Form
    {
        $bulkEditItemIds = request('bulk_edit_item_ids');
        $form->title(trans('arbory::resources.bulk_edit_form_title', ['count' => count($bulkEditItemIds)]));
        $form->setFields(function (Form\FieldSet $fieldSet) use ($bulkEditItemIds) {
            $fieldSet->hidden('bulk_edit_item_ids')->setValue(implode(',', $bulkEditItemIds));
        });

        $this->addCheckboxesToEachInput($form);

        return $form;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function confirmBulkEditDialog(Request $request): View
    {
        if (! $request->has('bulk_edit_item_ids')) {
            return view('arbory::dialogs.form_mass_empty');
        }
        $form = $this->buildBulkEditForm($this->resource());
        $this->additionalFormControls($form);

        return view('arbory::dialogs.form_mass_edit', [
            'formTarget' => $this->url('index'),
            'objectName' => $this->resource,
            'form' => $form->render(),
            'bulk_edit_item_ids' => '',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bulkUpdate(Request $request)
    {
        $bulEditItemIds = $request->input('resource.bulk_edit_item_ids');

        foreach (explode(',', $bulEditItemIds) as $id) {
            $resource = $this->resource()->find($id);
            $form = $this->buildBulkEditForm($resource);
            $this->prepareBulkFields($form);
            $form->update($request);
        }

        return $this->getAfterBulkEditResponse($request);
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function addCheckboxesToEachInput(Form $form): Form
    {
        $fieldSet = $form->fields();
        $items = $fieldSet->getFields();

        //count when iput added only, cache adjunctions in collection
        $counter = 0;

        foreach ($fieldSet->getIterator() as $key => $field) {
            if ($field->getName() == 'bulk_edit_item_ids') {
                continue;
            }

            $checkbox = $this->getInputControlCheckbox($field->getName(), $field->getLabel());

            $checkbox->setFieldSet($fieldSet);

            $fieldSet->offsetGet($key + $counter)
                ->addAttributes(['disabled' => 'disabled']);
            //Add ckeckbox before input
            $items->splice($key + $counter, 0, [$checkbox]);
            $counter++;
        }

        return $form;
    }

    /**
     * @param $fieldName
     * @param $fieldLabel
     * @return Form\Fields\Checkbox
     */
    protected function getInputControlCheckbox($fieldName, $fieldLabel): Form\Fields\Checkbox
    {
        $checkbox = new Form\Fields\Checkbox($fieldName.'_control');
        $checkbox->addAttributes(['data-target' => $fieldName])
            ->addClass('bulk-control')
            ->setLabel(trans('arbory::resources.check_to_change', ['input' => $fieldLabel]));

        return $checkbox;
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function prepareBulkFields(Form $form): Form
    {
        //change original
        $fieldSet = $form->fields();
        $items = $fieldSet->getFields();

        foreach ($fieldSet->getIterator() as $key => $field) {
            $name = $field->getName();
            $nameSpace = $form->getNamespace();
            $fieldName = $nameSpace.'.'.$name.'_control';
            if ($field->getName() != 'bulk_edit_item_ids' &&
                ! request()->has($fieldName)) {
                $items->forget($key);
            }
        }

        return $form;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function getAfterBulkEditResponse(Request $request)
    {
        return redirect()->back();
    }
}
