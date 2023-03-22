<?php

namespace Arbory\Base\Rules;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\ValidationRule;
use Laragear\TwoFactor\Contracts\TwoFactorAuthenticatable;

class Totp implements ValidationRule
{
    /**
     * Create a new "totp code" rule instance.
     *
     * @param Authenticatable|null $user
     */
    public function __construct(protected ?Authenticatable $user = null)
    {
        //
    }

    /**
     * Validate that an attribute is a valid Two-Factor Authentication TOTP code.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value)
            && $this->user instanceof TwoFactorAuthenticatable
            && ! $this->user->validateTwoFactorCode($value)) {
            $fail(__('two-factor::validation.totp_code'));
        }
    }
}
