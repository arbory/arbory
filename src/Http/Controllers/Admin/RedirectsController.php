<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Pages\Redirect;
use Illuminate\Routing\Controller;

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
    public function form(Form $form)
    {
        return $form->setFields(function (FieldSet $fields) {
            $fields->text('from_url')
                ->rules('required')
                ->setLabel(trans('arbory::redirect.from_url'));
            $fields->text('to_url')
                ->rules('required')
                ->setLabel(trans('arbory::redirect.to_url'));

            $fields->select('status')
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

    private function getStatusOptions()
    {
        $statusOptions = [];
        foreach ($this->resource::AVAILABLE_STATUSES as $status) {
            $statusOptions[$status] = trans('arbory::redirect.status.' . $status);
        }

        return $statusOptions;
    }
}
