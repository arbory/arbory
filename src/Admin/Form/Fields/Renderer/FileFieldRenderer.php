<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Files\ArboryFile;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Html;

/**
 * Class FileFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class FileFieldRenderer extends InputFieldRenderer
{
    /**
     * @var string
     */
    protected $type = 'item';

    /**
     * @return ArboryFile
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
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        $input = $this->getInput();
        $label = $input->getLabel( $this->field->getLabel() );

        $value = Html::div();

        $file = $this->getFile();

        if( $file )
        {
            $value->append( $this->createFileDetails( $file ) );
        }

        $value->append( $input );

        return $this->buildField( $label, $value );
    }

    /**
     * @param ArboryFile $file
     * @return Element
     */
    public function createFileDetails( ArboryFile $file ): Element
    {
        $fileSize = ( new FileSize( $file ) )->getReadableSize();
        $fileDetails = Html::div( $file->getOriginalName() . ' / ' . $fileSize );
        $removeInput =
            Html::button()->addClass( 'remove fa fa-times' )->addAttributes([
                'type' => 'submit',
                'name' => Element::formatName( $this->field->getNameSpacedName() . '.remove' ),
            ]);

        return $fileDetails->append( $removeInput );
    }
}
