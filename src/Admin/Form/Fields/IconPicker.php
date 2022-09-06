<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\IconPickerRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use SimpleXMLElement;

class IconPicker extends Select
{
    /**
     * @var string
     */
    protected $spritePath;

    /**
     * @var string
     */
    protected $filter;

    protected $rendererClass = IconPickerRenderer::class;

    protected $viewboxResolver;

    /**
     * @var array
     */
    protected $dimensions;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->spritePath = config('arbory.fields.sprite_icon.path');

        parent::__construct($name);
    }

    public function sprite(string $path): self
    {
        $this->spritePath = $path;

        return $this;
    }

    /**
     * @return $this
     */
    public function filter(string $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getOptions(): Collection
    {
        return $this->getIconIds()->mapWithKeys(fn ($iconId) => [$iconId => $iconId]);
    }

    /**
     * @param string $iconId
     * @return null|SimpleXMLElement
     */
    public function getIconContent($iconId)
    {
        $xml = simplexml_load_string(file_get_contents($this->spritePath));

        foreach ($xml->children()->symbol as $node) {
            /** @var SimpleXMLElement $node */
            $id = null;

            foreach ($node->attributes() as $attributeName => $attributeValue) {
                if ($attributeName === 'id') {
                    $id = (string) $attributeValue;
                }
            }

            if ($id === $iconId) {
                return $node;
            }
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function getIconIds(): Collection
    {
        $ids = new Collection();

        if (! file_exists($this->spritePath)) {
            $message = sprintf('Provided sprite-sheet [%s] doesn\'t exist', $this->spritePath);
            throw new InvalidArgumentException($message);
        }

        $xml = simplexml_load_string(file_get_contents($this->spritePath));

        foreach ($xml->children()->symbol as $node) {
            /** @var SimpleXMLElement $node */
            $id = null;

            foreach ($node->attributes() as $attributeName => $attributeValue) {
                if ($attributeName === 'id') {
                    $id = (string) $attributeValue;
                }
            }

            if ($this->filter && ! Str::contains($id, $this->filter)) {
                continue;
            }

            if ($id) {
                $ids->push($id);
            }
        }

        return $ids;
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function beforeModelSave(Request $request)
    {
        $this->options($this->getOptions());

        parent::beforeModelSave($request);
    }

    /**
     * @return callable|null
     */
    public function getViewboxResolver()
    {
        return $this->viewboxResolver;
    }

    /**
     * @param mixed $viewboxResolver
     * @return IconPicker
     */
    public function setViewboxResolver(callable $viewboxResolver)
    {
        $this->viewboxResolver = $viewboxResolver;

        return $this;
    }

    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    /**
     * @return IconPicker
     */
    public function setDimensions(?array $dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }
}
