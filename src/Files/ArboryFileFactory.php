<?php

namespace Arbory\Base\Files;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Repositories\ArboryFilesRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArboryFileFactory
{
    /**
     * @var ArboryFilesRepository
     */
    private $repository;

    /**
     * @param ArboryFilesRepository $repository
     */
    public function __construct(ArboryFilesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Model $model
     * @param UploadedFile|ArboryFile $file
     * @param string $relationName
     * @return ArboryFile
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function make(Model $model, $file, string $relationName)
    {
        $arboryFile = $this->writeFile($model, $file);
        $relation = $model->{$relationName}();

        if (! $relation instanceof BelongsTo) {
            throw new \InvalidArgumentException('Unsupported relation');
        }

        $localKey = explode('.', $relation->getQualifiedForeignKey())[1];

        $model->setAttribute($localKey, $arboryFile->getKey());
        $model->setRelation($relationName, $arboryFile);
        $model->save();

        return $arboryFile;
    }

    /**
     * @param Model $model
     * @param UploadedFile|ArboryFile $file
     * @return ArboryFile|null
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function writeFile(Model $model, $file)
    {
        $arboryFile = null;

        if ($file instanceof UploadedFile) {
            $arboryFile = $this->repository->createFromUploadedFile($file, $model);
        } elseif ($file instanceof ArboryFile) {
            $contents = $this->repository->getDisk()->get($file->getLocalName());
            $arboryFile = $this->repository->createFromBlob($file->getLocalName(), $contents, $model);
        } else {
            throw new \InvalidArgumentException('Invalid file source');
        }

        return $arboryFile;
    }
}
