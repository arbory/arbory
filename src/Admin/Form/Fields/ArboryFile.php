<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\FileFieldRenderer;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Repositories\ArboryFilesRepository;
use Illuminate\Http\Request;

/**
 * Class ArboryFile
 * @package Arbory\Base\Admin\Form\Fields
 */
class ArboryFile extends AbstractField
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
     * @return \Arbory\Base\Files\ArboryFile|null
     */
    public function getValue()
    {
        $value = parent::getValue();

        if ( is_string( $value ) )
        {
            $value = \Arbory\Base\Files\ArboryFile::where( 'id', $value )->first();
        }

        return $value;
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
        if ( $this->isRequired() )
        {
            return;
        }

        $arboryFilesRepository = app( 'arbory_files' );

        $currentFile = $this->getValue();

        if( $currentFile )
        {
            $arboryFilesRepository->delete( $currentFile->getKey() );
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

            $repository = new ArboryFilesRepository( $this->disk );

            $arboryFile = $repository->createFromUploadedFile( $uploadedFile, $this->getModel() );

            /**
             * @var $relation \Illuminate\Database\Eloquent\Relations\BelongsTo
             */
            $relation = $this->getModel()->{$this->getName()}();

            if( !$relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo )
            {
                throw new \InvalidArgumentException( 'Unsupported relation' );
            }

            $localKey = explode( '.', $relation->getQualifiedForeignKey() )[ 1 ];

            $this->getModel()->setAttribute( $localKey, $arboryFile->getKey() );
            $this->getModel()->setRelation( $this->getName(), $arboryFile );
            $this->getModel()->save();
        }
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return in_array( 'arbory_file_required', $this->getRules(), true );
    }
}
