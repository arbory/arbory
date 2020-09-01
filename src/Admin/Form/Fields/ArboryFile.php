<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;
use Arbory\Base\Files\ArboryFileFactory;
use Arbory\Base\Repositories\ArboryFilesRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Arbory\Base\Admin\Form\Fields\Renderer\FileFieldRenderer;

/**
 * Class ArboryFile.
 * @method \Arbory\Base\Files\ArboryFile getModel
 */
class ArboryFile extends ControlField
{
    /**
     * @var string
     */
    protected $elementType = 'input';

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'file',
    ];

    /**
     * @var string
     */
    protected $rendererClass = FileFieldRenderer::class;

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
    public function setDisk(string $disk)
    {
        $this->disk = $disk;
    }

    /**
     * @return \Arbory\Base\Files\ArboryFile|null
     */
    public function getValue()
    {
        $value = parent::getValue();

        if (is_string($value)) {
            $value = \Arbory\Base\Files\ArboryFile::where('id', $value)->first();
        }

        return $value;
    }

    /**
     * @return void
     */
    protected function deleteCurrentFileIfExists()
    {
        if ($this->isRequired() || $this->isDisabled()) {
            return;
        }

        $arboryFilesRepository = app('arbory_files');

        $currentFile = $this->getValue();

        if ($currentFile) {
            $arboryFilesRepository->delete($currentFile->getKey());
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function beforeModelSave(Request $request)
    {
    }

    /**
     * @param Request $request
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function afterModelSave(Request $request)
    {
        $input = $request->input($this->getNameSpacedName());
        $uploadedFile = $request->file($this->getNameSpacedName());

        if (array_get($input, 'remove')) {
            $this->deleteCurrentFileIfExists();
        }

        if ($uploadedFile) {
            $this->deleteCurrentFileIfExists();

            $model = $this->getModel();

            /**
             * @var BelongsTo
             */
            $relation = $model->{$this->getName()}();
            $modelClass = get_class($relation->getRelated());

            $factory = new ArboryFileFactory(
                new ArboryFilesRepository($this->getDisk(), $modelClass)
            );
            $factory->make($model, $uploadedFile, $this->getName());
        }
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return in_array('arbory_file_required', $this->getRules(), true) || $this->required;
    }
}
