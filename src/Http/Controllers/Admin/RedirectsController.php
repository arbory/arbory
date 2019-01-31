<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\Fields\Select;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Pages\Redirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class RedirectsController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Redirect::class;

    /**
     * @param Model $model
     * @return Form
     */
    protected function form(Model $model)
    {
        $form = $this->module()->form($model, function (Form $form) {
            $form->addField(new Text('from_url'))
                ->rules('required')
                ->setLabel(trans('arbory::redirect.from_url'));
            $form->addField(new Text('to_url'))
                ->rules('required')
                ->setLabel(trans('arbory::redirect.to_url'));

            $form->addField(new Select('status'))
                ->options($this->getStatusOptions())
                ->setLabel(trans('arbory::redirect.status.name'));
        });

        return $form;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        $grid = $this->module()->grid($this->resource(), function (Grid $grid) {
            $grid->column('from_url', trans('arbory::redirect.from_url'));
            $grid->column('to_url', trans('arbory::redirect.to_url'));
        });

        return $grid;
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
