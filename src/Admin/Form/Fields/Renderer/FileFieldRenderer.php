<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Html;

/**
 * Class FileFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class FileFieldRenderer extends BaseRenderer
{
    /**
     * @var string
     */
    protected $type = 'item';

    /**
     * @return Content
     */
    protected function getInput()
    {
        $content = new Content();

        $leafFile = $this->field->getValue();

        if( $leafFile )
        {
            $content->push( Html::div( $leafFile->getOriginalName() . ' / ' . $leafFile->getSize() ) );
        }

        $content->push( Html::input()->setType( 'file' )->setName( $this->field->getNameSpacedName() ) );

        return $content;
    }
}
