<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\Helpers\FileSize;
use Arbory\Base\Files\ArboryFile;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Support\Str;

/**
 * Class FileFieldRenderer.
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
        $value = Html::div();

        $file = $this->getFile();

        if ($file) {
            $value->append($this->createFileDetails($file));
        }

        if ($this->field->isInteractive() && ! $this->field->isDisabled()) {
            $value->append(parent::render());
        }

        return $value;
    }

    /**
     * @param  ArboryFile  $file
     * @return Element
     */
    public function createFileDetails(ArboryFile $file): Element
    {
        $removeInput = null;
        $removeButton = null;
        $fileSize = (new FileSize($file))->getReadableSize();

        $fileDetails = Html::div()->addClass('file-details');
        $downloadLink = Html::link(Str::limit($file->getOriginalName(), 20).' / '.$fileSize)->addAttributes([
            'href' => $file->getUrl(),
            'target' => '_blank',
            'title' => $file->getOriginalName(),
            'download',
        ]);

        if ($this->field->isInteractive() && ! $this->field->isDisabled()) {
            $removeButton =
                Html::button('delete')->addClass('remove mt-icon')->addAttributes([
                    'type' => 'button',
                ]);

            $removeInput = Html::input()
                ->setType('hidden')
                ->setName($this->field->getNameSpacedName().'.remove')
                ->setValue('')
                ->addClass('remove');
        }

        $fileDetails->append($downloadLink);

        if (! $this->field->isRequired()) {
            $fileDetails->append($removeButton);
            $fileDetails->append($removeInput);
        }

        return $fileDetails;
    }
}
