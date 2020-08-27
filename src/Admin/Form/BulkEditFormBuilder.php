<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;

/**
 * Class BulkEditFormBuilder.
 */
class BulkEditFormBuilder extends Builder
{
    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url($route, $parameters = [])
    {
        return $this->form->getModule()->url($route, $parameters);
    }

    /**
     * @return Element
     */
    protected function header()
    {
        return Html::header([
            Html::h1($this->form->getTitle()),
        ]);
    }

    /**
     * @return Element
     */
    protected function form()
    {
        $form = Html::form()->addAttributes([
            'id' => 'edit-resources',
            'class' => 'edit-resources',
            'novalidate' => 'novalidate',
            'enctype' => 'multipart/form-data',
            'accept-charset' => 'UTF-8',
            'method' => 'post',
            'action' => $this->form->getAction(),
            'data-remote' => 'true',
            'data-remote-validation' => 'true',
            'data-type' => 'json',
        ]);

        $form->append(csrf_field());

        $form->append(Html::input()->setName('_method')->setType('hidden')->setValue('POST'));

        return $form;
    }

    /**
     * @return Element
     */
    protected function footer()
    {
        $primary = Html::div()->addClass('primary');

        $primary->append(
            Html::link()
                ->addClass('button secondary with-icon')
                ->addAttributes(['data-type' => 'cancel'])
                ->append(Html::i('not_interested')->addClass('mt-icon'))
                ->append(trans('arbory::resources.cancel'))
        );

        $primary->append(
            Button::create('save', true)
                ->type('submit', 'primary')
                ->withIcon('check')
                ->disableOnSubmit()
                ->title(trans('arbory::resources.save'))
        );

        $footerTools = Html::div([
            $primary,
        ])->addClass('tools');

        return Html::footer($footerTools);
    }

    /**
     * @return Content|Element
     */
    public function render()
    {
        $content = Html::div()->addClass('body');

        $content->append($this->form->fields()->render());

        return new Content([
            Html::section(
                $this->form()
                    ->append($this->header())
                    ->append($content)
                    ->append($this->footer())
            ),
        ]);
    }
}
