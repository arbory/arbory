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
     * Slug constructor.
     * @param string $name
     * @param string $apiUrl
     */
    public function __construct( $name, $apiUrl )
    {
        $this->apiUrl = $apiUrl;

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
                'data-generator-url' => $this->apiUrl,
                'data-node-parent-id' => $this->getParentId()
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
            Html::div(
                Html::link(
                    $this->getLinkValue()
                )->addAttributes( [ 'href' => $this->getLinkHref() ] )
            )->addClass( 'link' ),
        ] )->addClass( 'field type-text' )->addAttributes( [ 'data-name' => 'slug' ] );
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

        return [ url( '/' ) . '/' . $urlToSlugElement . Html::span( $this->getSlug() ) ];
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

        return url( '/' ) . '/' . $urlToSlug . $this->getSlug();
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
    protected function getSlug()
    {
        $uriParts = explode( '/', $this->getUri() );

        return end( $uriParts );
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
        return $this->getUriToExistingModel() ?: $this->getUriToNewModel();
    }

    /**
     * @return int
     */
    protected function getParentId()
    {
        return request( 'parent_id' ) ?: $this->getModel()->getParentId();
    }
}
