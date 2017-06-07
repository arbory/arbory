<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\FileFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Repositories\LeafFilesRepository;
use Illuminate\Http\Request;

/**
 * Class LeafFile
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class LeafFile extends AbstractField
{
    /**
     * @var string
     */
    protected $disk = 'public';

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk;
    }

    /**
     * @param string $disk
     */
    public function setDisk( string $disk )
    {
        $this->disk = $disk;
    }

    /**
     * @return \CubeSystems\Leaf\Files\LeafFile|null
     */
    public function getValue()
    {
        return parent::getValue();
    }

    /**
     * @return Element
     */
    public function render()
    {
        return ( new FileFieldRenderer( $this ) )->render();
    }

    /**
     *
     */
    protected function deleteCurrentFileIfExists()
    {
        $leafFilesRepository = app( 'leaf_files' );

        $currentFile = $this->getValue();

        if( $currentFile )
        {
            $leafFilesRepository->delete( $currentFile->getKey() );
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function beforeModelSave( Request $request )
    {
    }

    /**
     * @param Request $request
     * @return void
     * @throws \InvalidArgumentException
     */
    public function afterModelSave( Request $request )
    {
        $input = $request->input( $this->getNameSpacedName() );
        $uploadedFile = $request->file( $this->getNameSpacedName() );

        if( $input && array_key_exists( 'remove', $input ) )
        {
            $this->deleteCurrentFileIfExists();
        }

        if( $uploadedFile )
        {
            $this->deleteCurrentFileIfExists();

            $repository = new LeafFilesRepository( $this->disk );

            $leafFile = $repository->createFromUploadedFile( $uploadedFile, $this->getModel() );

            /**
             * @var $relation \Illuminate\Database\Eloquent\Relations\BelongsTo
             */
            $relation = $this->getModel()->{$this->getName()}();

            if( !$relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo )
            {
                throw new \InvalidArgumentException( 'Unsupported relation' );
            }

            $localKey = explode( '.', $relation->getQualifiedForeignKey() )[ 1 ];

            $this->getModel()->setAttribute( $localKey, $leafFile->getKey() );
            $this->getModel()->setRelation( $this->getName(), $leafFile );
            $this->getModel()->save();
        }
    }
}
