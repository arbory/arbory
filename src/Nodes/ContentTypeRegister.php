<?php

namespace Arbory\Base\Nodes;

use Illuminate\Support\Collection;

/**
 * Class ContentTypesRepository.
 */
class ContentTypeRegister
{
    /**
     * @var Collection
     */
    protected $contentTypes;

    /**
     * ContentTypesRepository constructor.
     */
    public function __construct()
    {
        $contentTypes = collect(config('arbory.content_types', []));
        $contentTypeNames = $contentTypes->map(fn ($item) => new ContentTypeDefinition($item));

        $this->contentTypes = $contentTypes->combine($contentTypeNames);
    }

    /**
     * @return void
     */
    public function register(ContentTypeDefinition $definition)
    {
        $this->contentTypes->put($definition->getModel(), $definition);
    }

    /**
     * @return ContentTypeDefinition|null
     */
    public function findByModelClass(string $class)
    {
        return $this->contentTypes->get($class);
    }

    public function getAllowedChildTypes(Node $parent): Collection
    {
        if (! $parent->content || ! method_exists($parent->content, 'getAllowedChildTypes')) {
            return $this->getAllContentTypes();
        }

        $allowed = $parent->content->getAllowedChildTypes($parent, $this->getAllContentTypes());

        if (is_array($allowed)) {
            $allowed = new Collection($allowed);
        }

        return $this->mapToDefinitions($allowed);
    }

    /**
     * @return Collection|string[]
     */
    public function getAllContentTypes(): Collection|array
    {
        return $this->contentTypes;
    }

    /**
     * @param $type
     * @return bool
     */
    public function isValidContentType($type)
    {
        return $this->contentTypes->has($type);
    }

    protected function mapToDefinitions(Collection $mappable): Collection
    {
        return $mappable->mapWithKeys(function ($item) {
            if ($item instanceof ContentTypeDefinition) {
                return $item;
            }

            return [$item => $this->findByModelClass($item)];
        });
    }
}
