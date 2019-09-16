<?php

namespace Arbory\Base\Services\Authentication;

use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Foundation\Http\FormRequest;

interface AuthenticationMethod
{
    /**
     * @param array $credentials
     * @param bool $remember
     * @param bool $login
     * @return bool
     */
    public function authenticate(array $credentials, $remember = false, $login = true): bool;

    /**
     * @param UserInterface|null $user
     * @param bool $everywhere
     * @return bool
     */
    public function logout(UserInterface $user = null, $everywhere = false): bool;

    /**
     * @return FormRequest
     */
    public function getFormRequest(): FormRequest;

    /**
     * @return string
     */
    public function getLoginView(): string;
}
