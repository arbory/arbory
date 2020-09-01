<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Pages\Redirect;
use Illuminate\Routing\Controller;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Admin\Form\Fields\Select;

class RedirectsController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Redirect::class;

    /**
     * @param Form $form
     * @return Form
     */
    protected function form(Form $form)
    {
        return $form->setFields(function (Form\FieldSet $fieldSet) {
            $fieldSet->add(new Text('from_url'))
                ->rules('required')
                ->setLabel(trans('arbory::redirect.from_url'));

            $fieldSet->add(new Text('to_url'))
                ->rules('required')
                ->setLabel(trans('arbory::redirect.to_url'));

            $fieldSet->add(new Select('status'))
                ->options($this->getStatusOptions())
                ->setLabel(trans('arbory::redirect.status.name'));
        });
    }

    /**
     * @param Grid $grid
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        return $grid->setColumns(function (Grid $grid) {
            $grid->column('from_url', trans('arbory::redirect.from_url'));
            $grid->column('to_url', trans('arbory::redirect.to_url'));
        });
    }

    /**
     * @return array
     */
    private function getStatusOptions()
    {
        $statusOptions = [];
        foreach (Redirect::AVAILABLE_STATUSES as $status) {
            $statusOptions[$status] = trans('arbory::redirect.status.'.$status);
        }

        return $statusOptions;
    }
}
