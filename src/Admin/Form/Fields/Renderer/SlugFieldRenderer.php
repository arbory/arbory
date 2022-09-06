<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class SlugFieldRenderer extends ControlFieldRenderer
{

    /**
     * Get the evaluated contents of the object.
     */
    public function render(): Content
    {
        $control = $this->getControl();
        $control = $this->configureControl($control);

        $control->addAttributes([
            'data-generator-url' => $this->field->getApiUrl(),
            'data-from-field-name' => $this->field->getFromFieldName(),
            'data-node-parent-id' => $this->field->getParentId(),
            'data-model-table' => $this->field->getModel()->getTable(),
            'data-object-id' => $this->field->getModel()->getKey(),
        ]);

        $button = Button::create()
            ->type('button', 'secondary generate')
            ->title(trans('arbory::fields.slug.suggest_slug'))
            ->withIcon('keyboard')
            ->iconOnly()
            ->render();

        $content = new Content();

        $content->push(Html::div([$control->render($control->element()), $button])->addClass('value'));
        $content->push($this->getLinkElement());

        if (config('arbory.preview.enabled')) {
            $content->push($this->getPreviewLinkElement());
        }

        return $content;
    }

    /**
     * @return Element|null
     */
    protected function getLinkElement()
    {
        if (! $this->field->hasUriToSlug()) {
            return;
        }

        return Html::div(
            Html::link(
                $this->getLinkValue()
            )->addAttributes(['href' => $this->field->getLinkHref()])
        )->addClass('link');
    }

    /**
     * @return Element|null
     */
    protected function getPreviewLinkElement()
    {
        if (! $this->field->hasUriToSlug() || $this->field->getModel()->isActive() || ! $this->field->getValue()) {
            return;
        }

        return Html::div(
            Html::link(
                trans('arbory::fields.slug.page_preview')
            )->addAttributes(['href' => $this->field->getPreviewLinkHref()])
        )->addClass('link');
    }

    /**
     * @return array
     */
    protected function getLinkValue()
    {
        $urlToSlug = ltrim($this->field->getUriToSlug(), '/');
        $urlToSlugElement = Html::span($urlToSlug);

        return [
            [
                url('/'),
                '/',
                $urlToSlugElement,
                ($urlToSlug) ? '/' : '',
            ],
            Html::span($this->field->getValue()),
        ];
    }

    /**
     * @return mixed
     */
    public function setField(FieldInterface $field): RendererInterface
    {
        $this->field = $field;
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }

    /**
     * Configure the style before rendering the field.
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        return $options;
    }
}
