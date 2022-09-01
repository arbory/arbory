<?php

namespace Arbory\Base\Files;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use InvalidArgumentException;
use RuntimeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Repositories\ArboryFilesRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArboryFileFactory
{
    public function __construct(private ArboryFilesRepository $repository)
    {
    }

    /**
     * @return ArboryFile
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function make(Model $model, UploadedFile|ArboryFile $file, string $relationName)
    {
        $arboryFile = $this->writeFile($model, $file);
        $relation = $model->{$relationName}();

        if (! $relation instanceof BelongsTo) {
            throw new InvalidArgumentException('Unsupported relation');
        }

        $localKey = explode('.', $relation->getQualifiedForeignKeyName())[1];

        $model->setAttribute($localKey, $arboryFile->getKey());
        $model->setRelation($relationName, $arboryFile);
        $model->save();

        return $arboryFile;
    }

    /**
     * @return ArboryFile|null
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws FileNotFoundException
     */
    private function writeFile(Model $model, UploadedFile|ArboryFile $file)
    {
        $arboryFile = null;

        if ($file instanceof UploadedFile) {
            $arboryFile = $this->repository->createFromUploadedFile($file, $model);
        } elseif ($file instanceof ArboryFile) {
            $contents = $this->repository->getDisk()->get($file->getLocalName());
            $arboryFile = $this->repository->createFromBlob($file->getLocalName(), $contents, $model);
        } else {
            throw new InvalidArgumentException('Invalid file source');
        }

        return $arboryFile;
    }
}
