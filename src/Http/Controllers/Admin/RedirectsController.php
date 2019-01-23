<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Form;
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
    protected function form(Form $form)
    {
        return $form->setFields(function (Form\FieldSet $fields) {
            $fields->text('from_url')->rules('required');
            $fields->text('to_url')->rules('required');
        });
    }

    /**
     * @param Grid $grid
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        return $grid->setColumns(function (Grid $grid) {
            $grid->column('from_url');
            $grid->column('to_url');
        });
    }
}
