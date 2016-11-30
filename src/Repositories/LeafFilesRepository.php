<?php

namespace CubeSystems\Leaf\Repositories;

use CubeSystems\Leaf\Files\LeafFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Storage;

/**
 * Class LeafFileRepository
 * @package CubeSystems\Leaf\Repositories
 */
class LeafFilesRepository extends AbstractModelsRepository
{
    /**
     * @var string
     */
    protected $modelClass = LeafFile::class;

    /**
     * @var string
     */
    protected $diskName;

    /**
     * @var string
     */
    protected $disk;

    /**
     * LeafFileRepository constructor.
     * @param string $diskName
     * @param string $leafFileClass
     */
    public function __construct( $diskName, $leafFileClass = LeafFile::class )
    {
        $this->diskName = $diskName;
        $this->modelClass = $leafFileClass;

        parent::__construct();
    }

    /**
     * @return \Illuminate\Filesystem\FilesystemAdapter|string
     */
    public function getDisk()
    {
        if( !$this->disk )
        {
            $this->disk = Storage::disk( $this->diskName );
        }

        return $this->disk;
    }

    /**
     * @param UploadedFile $file
     * @param Model $owner
     * @return LeafFile|null
     */
    public function createFromUploadedFile( UploadedFile $file, Model $owner )
    {
        if( !$file->getRealPath() )
        {
            throw new \RuntimeException( 'Uploaded file does not have real path' );
        }

        $localFileName = $this->getLocalFilenameForUploadedFile( $file );

        if( !$this->getDisk()->put( $localFileName, file_get_contents( $file->getRealPath() ) ) )
        {
            throw new \RuntimeException( 'Could not store local file "' . $localFileName . '"' );
        }

        $modelClass = $this->modelClass;
        $leafFile = new $modelClass(
            $this->getCreateAttributesForCreatedFile( $file, $localFileName, $owner ),
            $localFileName
        );

        /* @var $leafFile LeafFile */
        if( !$leafFile->save() )
        {
            throw new \RuntimeException( 'Could not save "' . $modelClass . '" to database' );
        }

        return $leafFile;
    }


    /**
     * @param $fileName
     * @param $fileContents
     * @param Model $owner
     * @return LeafFile|null
     */
    public function createFromBlob( $fileName, $fileContents, Model $owner )
    {
        $localFileName = $this->getLocalFilenameForBlob( $fileContents );

        if( !$this->getDisk()->put( $localFileName, $fileContents ) )
        {
            throw new \RuntimeException( 'Could not store local file "' . $localFileName . '"' );
        }

        $modelClass = $this->modelClass;
        $leafFile = new $modelClass(
            $this->getCreateAttributesForBlob( $fileName, $fileContents, $localFileName, $owner )
        );

        /* @var $leafFile LeafFile */
        if( !$leafFile->save() )
        {
            throw new \RuntimeException( 'Could not save "' . $modelClass . '" to database' );
        }

        return $leafFile;
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function getFreeFileName( $fileName )
    {
        $uploadsDisk = $this->getDisk();

        while( $uploadsDisk->exists( $fileName ) )
        {
            $fileNameParts = pathinfo( $fileName );
            $fileName = $fileNameParts['filename'] . '-' . str_random( 10 );
        }

        return $fileName;
    }

    /**
     * @param UploadedFile $file
     * @param string $localFileName
     * @return array
     */
    protected function getCreateAttributesForCreatedFile( UploadedFile $file, $localFileName, Model $owner )
    {
        $realPath = $file->getRealPath();

        return [
            'disk' => $this->diskName,
            'sha1' => sha1_file( $realPath ),
            'original_name' => $file->getClientOriginalName(),
            'local_name' => $localFileName,
            'size' => $file->getClientSize(),
            'owner_id' => $owner->getKey(),
            'owner_type' => $owner->getMorphClass()
        ];
    }

    /**
     * @param string $originalFileName
     * @param string $fileContents
     * @param string $localFileName
     * @return array
     */
    protected function getCreateAttributesForBlob( $originalFileName, $fileContents, $localFileName, Model $owner )
    {
        return [
            'disk' => $this->diskName,
            'sha1' => sha1( $fileContents ),
            'original_name' => $originalFileName,
            'local_name' => $localFileName,
            'size' => strlen( $fileContents ),
            'owner_id' => $owner->getKey(),
            'owner_type' => $owner->getMorphClass()
        ];
    }

    /**
     * @param $fileContents
     * @return string
     */
    protected function getLocalFilenameForBlob( $fileContents ): string
    {
        return $this->getFreeFileName( sha1( $fileContents ) );
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getLocalFilenameForUploadedFile( UploadedFile $file ): string
    {
        return $this->getFreeFileName( sha1_file( $file->getRealPath() ) );
    }

    /**
     * @param string $leafFileId
     * @return int
     */
    public function delete( $leafFileId )
    {
        $leafFile = $this->find( $leafFileId );
        /* @var $leafFile LeafFile */
        $this->getDisk()->delete( $leafFile->getLocalName() );

        return parent::delete( $leafFileId );
    }
}
