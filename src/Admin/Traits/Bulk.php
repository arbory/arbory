<?php

namespace Arbory\Base\Admin\Traits;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\MassFormBuilder;
use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Module;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait Bulk
{

    /**
     * @param Form $form
     * @return Form
     */
    protected function massForm(Form $form)
    {
        return $form;
    }

    /**
     * @param Model $model
     * @return Form
     */
    protected function buildMassForm(Model $model, $ids = false, $withChecks = true)
    {
        $form = new Form($model);
        $form->setAction($this->url('massupdate', $model->getKey()));
        $form->setModule($this->module());
        $form->setRenderer(new MassFormBuilder($form));
        if($ids){
            $form->title(trans('arbory::resources.mass_form_title', ['count' => count($ids)]));
            $form->setFields(function (Form\FieldSet $fieldSet) use ($ids) {
                $fieldSet->hidden('ids')->setValue(implode(',', $ids));
            });
        }
        $this->massForm($form);

        if($withChecks) {
            $this->addCheckboxesToEachInput($form);
        } else {
            $this->preprocessMassUpdate($form);
        }

        return $form;
    }

    protected function confirmMassEditDialog(Request $request)
    {
        if(!$request->has('ids')){
            return view('arbory::dialogs.form_mass_empty');
        }
        return view('arbory::dialogs.form_mass_edit', [
            'formTarget' => $this->url('index'),
            'objectName' => $this->resource,
            'form' => $this->buildMassForm($this->resource(), $request->get('ids'))->render(),
            'ids'  => ''
        ]);
    }

    /**
     * @param Request $request
     * @param $resourceId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function massUpdate(Request $request)
    {
        $form = $this->buildMassForm($this->resource(), false, true);

        $form->validate();

        if ($request->ajax()) {
            return response()->json(['ok']);
        }

        $input = $request->all('resource.ids');
        $ids = isset($input['resource']['ids']) ? $input['resource']['ids'] : '';
        $request->request->add(['fields' => $form->fields()]);

        foreach (explode(',', $ids) as $id) {
            $resource = $this->resource()->find($id);
            $form = $this->buildMassForm($resource, false, false);
            $form->update($request);
        }

        return $this->getAfterMassEditResponse($request);
    }

    /**
     * @param $form
     */
    protected function addCheckboxesToEachInput($form){
        //change original
        $originalFields = $form->fields();

        //iterate clone
        $clonedFields = clone $originalFields;

        //count when iput added only, cache adjunctions in collection
        $counter = 0;

        $clonedFields->each(function($field, $key) use ($originalFields, &$counter){
            $type = $field->getFieldTypeName();
            if($type != 'type-hidden'){
                $checkbox = new Form\Fields\Checkbox($field->getName().'_control');
                $checkbox->addAttributes(['data-target' => $field->getName()]);
                $checkbox->addClass('bulk-control');
                $checkbox->setFieldSet($originalFields);
                //Empty checkboxes
                if($type != 'type-checkbox')
                    $originalFields[$key+$counter]->rules('required_with:resource.'.$field->getName().'_control');
                $originalFields[$key+$counter]->addAttributes(['disabled' => 'disabled']);
                //Add ckeckbox before input
                $originalFields->splice($key+$counter, 0, [$checkbox]);
                $counter++;
            }
        });

    }

    /**
     * @param $form
     */
    protected function preprocessMassUpdate($form)
    {
        $request = request();

        //change original
        $originalFields = $form->fields();

        //iterate clone
        $clonedFields = clone $originalFields;

        $clonedFields->each(function($field, $key) use ($originalFields, $request){
            if($field->getFieldTypeName() != 'type-hidden' &&
                !$request->has($originalFields->getNamespace().'.'.$field->getName().'_control')) {
                $originalFields->forget($key);
            }
        });

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function getAfterMassEditResponse(Request $request)
    {
        return redirect($this->module()->url('index'));
    }
}
