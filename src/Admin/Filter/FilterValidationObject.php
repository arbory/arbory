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
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->rules = $data['rules'] ?? [];
        $this->messages = $data['messages'] ?? [];
        $this->attributes = $data['attributes'] ?? [];
        $this->transformers = $data['transformers'] ?? [];
    }

    /**
     * @param array $rules
     * @return $this
     */
    public function addRules(array $rules): self
    {
        array_push($this->rules, $rules);

        return $this;
    }

    /**
     * @param array $messages
     * @return $this
     */
    public function addMessages(array $messages): self
    {
        array_push($this->messages, $messages);

        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes): self
    {
        array_push($this->attributes, $attributes);

        return $this;
    }

    /**
     * @param array $transformers
     * @return $this
     */
    public function addTransformers(array $transformers): self
    {
        array_push($this->transformers, $transformers);

        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return array_merge([], ...$this->rules);
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return array_merge([], ...$this->messages);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return array_merge([], ...$this->attributes);
    }

    /**
     * @return array
     */
    public function getTransformers(): array
    {
        return $this->transformers;
    }
}
