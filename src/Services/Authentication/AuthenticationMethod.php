<?php

namespace Arbory\Base\Services\Authentication;

use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Foundation\Http\FormRequest;

interface AuthenticationMethod
{
    /**
     * @param  bool  $remember
     * @param  bool  $login
     */
    public function authenticate(array $credentials, $remember = false, $login = true): bool;

    /**
     * @param  UserInterface|null  $user
     * @param  bool  $everywhere
     */
    public function logout(UserInterface $user = null, $everywhere = false): bool;

    public function getFormRequest(): FormRequest;

    public function getLoginView(): string;
}
