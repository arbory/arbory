<?php

namespace Arbory\Base\Services\Permissions;

class ModulePermission
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $translationKeyPrefix = 'arbory::permissions.';

    /**
     * @var bool
     */
    private $allowed;

    /**
     * ModulePermission constructor.
     *
     * @param  string  $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTranslation(): string
    {
        return trans($this->translationKeyPrefix . $this->name);
    }

    /**
     * @param  bool  $allowed
     */
    public function setAllowed(bool $allowed)
    {
        $this->allowed = $allowed;
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->allowed;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTranslation();
    }
}
