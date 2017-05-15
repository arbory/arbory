<?php declare( strict_types=1 );

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\ImageFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class LeafImage
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
final class LeafImage extends LeafFile
{
    /**
     * @return Element
     */
    public function render(): Element
    {
        return ( new ImageFieldRenderer( $this ) )->render();
    }
}
