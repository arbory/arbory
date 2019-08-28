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
     * @param array $rule
     */
    public function addRule(array $rule)
    {
        array_push($this->rules, $rule);
    }

    /**
     * @param array $message
     */
    public function addMessage(array $message)
    {
        array_push($this->messages, $message);
    }

    /**
     * @param array $attribute
     */
    public function addAttribute(array $attribute)
    {
        array_push($this->attributes, $attribute);
    }

    /**
     * @param array $transformer
     */
    public function addTransformer(array $transformer)
    {
        array_push($this->transformers, $transformer);
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getTranformers(): array
    {
        return $this->transformers;
    }

    /**
     * @return array
     */
    public function getAllForValidator(): array
    {
        return [
            array_merge([], ...$this->getRules()),
            array_merge([], ...$this->getMessages()),
            array_merge([], ...$this->getAttributes()),
        ];
    }
}