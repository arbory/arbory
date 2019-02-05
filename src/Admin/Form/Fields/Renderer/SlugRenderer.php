<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer;


use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Arbory\Base\Admin\Form\Fields\Slug;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Arbory\Base\Nodes\Node;

class SlugRenderer implements RendererInterface
{
    /**
     * @var Slug
     */
    protected $field;

    /**
     * SlugRenderer constructor.
     *
     * @param FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $input = Html::input()
                     ->setName($this->field->getNameSpacedName())
                     ->setValue($this->field->getValue())
                     ->addClass('text')
                     ->addAttributes([
                         'data-generator-url' => $this->field->getApiUrl(),
                         'data-from-field-name' => $this->field->getFromFieldName(),
                         'data-node-parent-id' => $this->field->getParentId(),
                         'data-model-table' => $this->field->getModel()->getTable(),
                         'data-object-id' => $this->field->getModel()->getKey()
                     ]);

        $button = Button::create()
                        ->type('button', 'secondary generate')
                        ->title(trans('arbory::fields.slug.suggest_slug'))
                        ->withIcon('keyboard-o')
                        ->iconOnly()
                        ->render();

        $content = new Content();

        $content->push(Html::div([$input, $button])->addClass('value'));
        $content->push($this->getLinkElement());
        $content->push($this->getPreviewLinkElement());

        return $content;
    }


    /**
     * @return Element|null
     */
    protected function getLinkElement()
    {
        if (! $this->hasUriToSlug()) {
            return null;
        }

        return Html::div(
            Html::link(
                $this->getLinkValue()
            )->addAttributes(['href' => $this->getLinkHref()])
        )->addClass('link');
    }


    /**
     * @return Element|null
     */
    protected function getPreviewLinkElement()
    {
        if (! $this->hasUriToSlug() || $this->field->getModel()->isActive() || !$this->field->getValue()) {
            return null;
        }

        return Html::div(
            Html::link(
                trans('arbory::fields.slug.page_preview')
            )->addAttributes(['href' => $this->getPreviewLinkHref()])
        )->addClass('link');
    }

    /**
     * @return array
     */
    protected function getLinkValue()
    {
        $urlToSlug = ltrim($this->field->getUriToSlug(), "/");
        $urlToSlugElement = Html::span( $urlToSlug );

        return [
            [
                url('/'),
                '/',
                $urlToSlugElement,
                ($urlToSlug) ? '/' : ''
            ],
            Html::span($this->field->getValue())
        ];
    }

    /**
     * @return string
     */
    protected function getLinkHref()
    {
        $urlToSlug = $this->field->getUriToSlug();

        if ($urlToSlug) {
            $urlToSlug .= '/';
        }

        return url($urlToSlug . $this->field->getValue());
    }

    /**
     * @return string
     */
    protected function getPreviewLinkHref()
    {
        $urlToSlug = $this->field->getUriToSlug();

        if ($urlToSlug) {
            $urlToSlug .= '/';
        }

        $slugHashed = 'preview-' . sha1('__cms-preview' . '/' . $urlToSlug . $this->field->getValue());

        return url($slugHashed);
    }

    /**
     * @return bool
     */
    protected function hasUriToSlug(): bool
    {
        return $this->field->getModel() instanceof Node;
    }

    /**
     * @param FieldInterface $field
     *
     * @return mixed
     */
    public function setField( FieldInterface $field ): RendererInterface
    {
        $this->field = $field;
    }

    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface
    {
        return $this->field;
    }

    /**
     * Configure the style before rendering the field
     *
     * @param StyleOptionsInterface $options
     *
     * @return StyleOptionsInterface
     */
    public function configure( StyleOptionsInterface $options ): StyleOptionsInterface
    {
        return $options;
    }
}