<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use CubeSystems\Leaf\Repositories\LeafFilesRepository;
use Illuminate\Database\Eloquent\Model;
use Input;

/**
 * Class LeafFile
 * @package CubeSystems\Leaf\Fields
 */
class LeafFile extends AbstractField
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return Element
     */
    public function render()
    {
        $leafFile = $this->getModel()->getAttribute( $this->getName() );
        $fileDescription = null;

        if( $leafFile )
        {
            $fileDescription = Html::div( $leafFile->getOriginalName() . ' / ' . $leafFile->getSize() );
        }

        $fileInput = Html::input()->setType( 'file' )->setName( $this->getNameSpacedName() );

        return Html::div( [
            Html::div( $fileInput->getLabel( $this->getLabel() ) )->addClass( 'label-wrap' ),
            Html::div( [ $fileDescription, $fileInput ] )->addClass( 'value' )
        ] )->addClass( 'field type-item' );
    }

    /**
     * @param Model $model
     * @param array $input
     */
    public function afterModelSave( Model $model, array $input = [] )
    {
        /**
         * @var $leafFilesRepository LeafFilesRepository
         * @var $currentLeafFile \CubeSystems\Leaf\Files\LeafFile
         */

        // TODO: Fix $input to have posted files somehow
        $allFiles = array_get( Input::allFiles(), 'resource' );

        $uploadedFile = array_get( $allFiles, $this->getName() );

        if( $uploadedFile )
        {
            $leafFilesRepository = app( 'leaf_files' );
            $currentLeafFile = $model->{$this->getName()};

            if( $currentLeafFile )
            {
                $leafFilesRepository->delete( $currentLeafFile->getKey() );
            }

            $leafFile = app( 'leaf_files' )->createFromUploadedFile( $uploadedFile, $model );

            $model->{$this->getName()}()->associate( $leafFile );
        }
    }
}
