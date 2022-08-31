<?php

namespace Arbory\Base\Admin\Filter;

class FilterValidationObject
{
    /**
     * @var array
     */
    protected $rules;

    /**
     * @var array
     */
    protected $messages;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $transformers;

    /**
     * FilterValidationObject constructor.
     */
    public function __construct(array $data = [])
    {
        $this->rules = $data['rules'] ?? [];
        $this->messages = $data['messages'] ?? [];
        $this->attributes = $data['attributes'] ?? [];
        $this->transformers = $data['transformers'] ?? [];
    }

    /**
     * @return $this
     */
    public function addRules(array $rules): self
    {
        array_push($this->rules, $rules);

        return $this;
    }

    /**
     * @return $this
     */
    public function addMessages(array $messages): self
    {
        array_push($this->messages, $messages);

        return $this;
    }

    /**
     * @return $this
     */
    public function addAttributes(array $attributes): self
    {
        array_push($this->attributes, $attributes);

        return $this;
    }

    /**
     * @return $this
     */
    public function addTransformers(array $transformers): self
    {
        array_push($this->transformers, $transformers);

        return $this;
    }

    public function getRules(): array
    {
        return array_merge([], ...$this->rules);
    }

    public function getMessages(): array
    {
        return array_merge([], ...$this->messages);
    }

    public function getAttributes(): array
    {
        return array_merge([], ...$this->attributes);
    }

    public function getTransformers(): array
    {
        return $this->transformers;
    }
}
