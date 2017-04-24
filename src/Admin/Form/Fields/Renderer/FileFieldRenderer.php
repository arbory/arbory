<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Files\LeafFile;
use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Inputs\Input;
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
     * @return Input
     */
    protected function getInput()
    {
        return Html::input()->setType( 'file' )->setName( $this->field->getNameSpacedName() );
    }

    /**
     * @return \CubeSystems\Leaf\Html\Elements\Element
     */
    public function render()
    {
        $input = $this->getInput();
        $label = $input->getLabel( $this->field->getLabel() );

        $value = Html::div();

        $leafFile = $this->getFile();

        if( $leafFile )
        {
            $fileDetails = Html::div( $leafFile->getOriginalName() . ' / ' . $this->getReadableFileSize( $leafFile ) );
            $fileDetails->append( Html::button()->addClass( 'remove fa fa-times' ) );
            $value->append( $fileDetails );
        }

        $value->append( $input );

        return $this->buildField( $label, $value );
    }

    /**
     * @param LeafFile $file
     * @return string
     */
    private function getReadableFileSize( LeafFile $file ): string
    {
        return ( new FileSize( $file ) )->getReadableSize();
    }
}
