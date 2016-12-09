<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Repositories\LeafFilesRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\View\View;
use Input;

/**
 * Class LeafFile
 * @package CubeSystems\Leaf\Fields
 */
class LeafFile extends AbstractField
{
    /**
     * @return View
     */
    public function render()
    {
        return view( $this->getViewName(), [
            'field' => $this,
            'leaf_file' => $this->getModel()->getAttribute( $this->getName() )
        ] );
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
