<?php declare( strict_types=1 );

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Inputs\Input;

/**
 * Class ImageFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
final class ImageFieldRenderer extends FileFieldRenderer
{
    /**
     * @return Input
     */
    protected function getInput(): Input
    {
        return parent::getInput()->addAttributes( [
            'accept' => 'image/*'
        ] );
    }
}
