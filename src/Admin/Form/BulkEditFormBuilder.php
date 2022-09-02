<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

/**
 * Class BulkEditFormBuilder.
 */
class BulkEditFormBuilder extends Builder
{
    protected function header(): Element
    {
        return Html::header([
            Html::h1($this->form->getTitle()),
        ]);
    }

    protected function form(): Element
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

    protected function footer(): Element
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

    public function render(): Content
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
