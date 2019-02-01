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
class FileFieldRenderer extends ControlFieldRenderer
{
    /**
     * @var \Arbory\Base\Admin\Form\Fields\ArboryFile
     */
    protected $field;

    /**
     * @return ArboryFile
     */
    protected function getFile()
    {
        return $this->field->getValue();
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        $input = parent::render();

        $value = Html::div();

        $file = $this->getFile();

        if( $file )
        {
            $value->append( $this->createFileDetails( $file ) );
        }

        $value->append( $input );

        return $value;
    }

    /**
     * @param ArboryFile $file
     * @return Element
     */
    public function createFileDetails(ArboryFile $file): Element
    {
        $fileSize = (new FileSize($file))->getReadableSize();

        $fileDetails = Html::div()->addClass('file-details');
        $downloadLink = Html::a(str_limit($file->getOriginalName(), 20) . ' / ' . $fileSize)->addAttributes([
            'href' => $file->getUrl(),
            'target' => '_blank',
            'title' => $file->getOriginalName(),
            'download'
        ]);
        $removeButton =
            Html::button()->addClass('remove fa fa-times')->addAttributes([
                'type' => 'button',
            ]);

        $removeInput = Html::input()
            ->setType('hidden')
            ->setName($this->field->getNameSpacedName() . '.remove')
            ->setValue('')
            ->addClass('remove');

        $fileDetails->append($downloadLink);

        if (!$this->field->isRequired()) {
            $fileDetails->append($removeButton);
            $fileDetails->append($removeInput);
        }

        return $fileDetails;
    }
}
