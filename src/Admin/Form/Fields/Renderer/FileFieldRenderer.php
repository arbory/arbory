<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Files\LeafFile;
use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Html;

/**
 * Class FileFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class FileFieldRenderer extends InputFieldRenderer
{
    /**
     * @var string
     */
    protected $type = 'item';

    /**
     * @return LeafFile
     */
    protected function getFile()
    {
        return $this->field->getValue();
    }

    /**
     * @return Content
     */
    protected function getInput()
    {
        $content = new Content();

        $leafFile = $this->getFile();

        if( $leafFile )
        {
            $content->push( Html::div( $leafFile->getOriginalName() . ' / ' . $leafFile->getSize() ) );
        }

        $content->push( Html::input()->setType( 'file' )->setName( $this->field->getNameSpacedName() ) );

        return $content;
    }
}
