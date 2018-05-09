<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Arbory\Base\Nodes\Node;
use Arbory\Base\Repositories\NodesRepository;

/**
 * Class Slug
 * @package Arbory\Base\Admin\Form\Fields
 */
class Slug extends AbstractField
{
    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $fromFieldName;

    /**
     * @param string $name
     * @param string $fromFieldName
     * @param string $apiUrl
     */
    public function __construct( $name, $fromFieldName, $apiUrl )
    {
        $this->apiUrl = $apiUrl;
        $this->fromFieldName = $fromFieldName;

        parent::__construct( $name );
    }

    /**
     * @return Element
     */
    public function render()
    {
        $label = Html::label( $this->getLabel() )->addAttributes( [ 'for' => $this->getNameSpacedName() ] );

        $input = Html::input()
            ->setName( $this->getNameSpacedName() )
            ->setValue( $this->getValue() )
            ->addClass( 'text' )
            ->addAttributes( [
                'data-generator-url' => $this->getApiUrl(),
                'data-from-field-name' => $this->getFromFieldName(),
                'data-node-parent-id' => $this->getParentId(),
                'data-model-table' => $this->getModel()->getTable(),
                'data-object-id' => $this->getModel()->getKey()
            ] );

        $button = Button::create()
            ->type( 'button', 'secondary generate' )
            ->title( trans( 'arbory::fields.slug.suggest_slug' ) )
            ->withIcon( 'keyboard-o' )
            ->iconOnly()
            ->render();

        return Html::div( [
            Html::div( $label )->addClass( 'label-wrap' ),
            Html::div( [ $input, $button ] )->addClass( 'value' ),
            $this->getLinkElement(),
        ] )->addClass( 'field type-slug' )->addAttributes( [ 'data-name' => 'slug' ] );
    }

    /**
     * @return Element|null
     */
    protected function getLinkElement()
    {
        if( !$this->hasUriToSlug() )
        {
            return null;
        }

        return Html::div(
            Html::link(
                $this->getLinkValue()
            )->addAttributes( [ 'href' => $this->getLinkHref() ] )
        )->addClass( 'link' );
    }

    /**
     * @return array
     */
    protected function getLinkValue()
    {
        $urlToSlug = $this->getUriToSlug();
        $urlToSlugElement = Html::span( $this->getUriToSlug() );

        if( $urlToSlug )
        {
            $urlToSlugElement .= '/';
        }

        return [
            [
                url( '/' ),
                '/',
                $urlToSlugElement,
            ],
            Html::span( $this->getValue() )
        ];
    }

    /**
     * @return string
     */
    protected function getLinkHref()
    {
        $urlToSlug = $this->getUriToSlug();

        if( $urlToSlug )
        {
            $urlToSlug .= '/';
        }

        return url( '/' ) . '/' . $urlToSlug . $this->getValue();
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        return $this->getModel()->getUri();
    }

    /**
     * @return string
     */
    protected function getUriToExistingModel()
    {
        $uriParts = explode( '/', $this->getUri() );

        array_pop( $uriParts );

        return implode( '/', $uriParts );
    }

    /**
     * @return string
     */
    protected function getUriToNewModel()
    {
        /**
         * @var Node $parentNode
         */
        $repository = new NodesRepository;
        $parentNode = $repository->find( $this->getParentId() );

        return $parentNode ? $parentNode->getUri() : (string) null;
    }

    /**
     * @return string
     */
    protected function getUriToSlug()
    {
        if( !$this->getModel() instanceof Node )
        {
            return false;
        }

        return $this->getUriToExistingModel() ?: $this->getUriToNewModel();
    }

    /**
     * @return bool
     */
    protected function hasUriToSlug(): bool
    {
        return $this->getModel() instanceof Node;
    }

    /**
     * @return int
     */
    protected function getParentId()
    {
        $model = $this->getModel();

        if( $model instanceof Node )
        {
            return $this->getModel()->getParentId();
        }

        return request( 'parent_id' );
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @return string
     */
    public function getFromFieldName(): string
    {
        return $this->fromFieldName;
    }
}
