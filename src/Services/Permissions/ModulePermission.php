<?php

namespace Arbory\Base\Services\Permissions;

class ModulePermission
{
    private string $translationKeyPrefix = 'arbory::permissions.';

    private ?bool $allowed = null;

    /**
     * ModulePermission constructor.
     */
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTranslation(): string
    {
        return trans($this->translationKeyPrefix . $this->name);
    }

    public function setAllowed(bool $allowed)
    {
        $this->allowed = $allowed;
    }

    public function isAllowed(): bool
    {
        return $this->allowed;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTranslation();
    }
}
