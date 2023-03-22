<?php

namespace Arbory\Base\Services\Authentication\Drivers;

use Arbory\Base\Services\Authentication\Drivers\Sentinel\SentinelService;
use Illuminate\Support\Arr;
use Arbory\Base\Http\Requests\LoginRequest;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Foundation\Http\FormRequest;
use Arbory\Base\Services\Authentication\AuthenticationMethod;

class Sentinel implements AuthenticationMethod
{
    protected const LOGIN_VIEW = 'arbory::controllers.security.login';

    private SentinelService $sentinel;

    public function __construct()
    {
        $this->sentinel = app('sentinel');
    }

    /**
     * @param  array  $credentials
     * @param  bool  $remember
     * @param  bool  $login
     * @return bool
     */
    public function authenticate(array $credentials, $remember = false, $login = true): bool
    {
        $user = $this->sentinel->authenticate(Arr::get($credentials, 'user', []), $remember, $login);

        return $user !== false;
    }

    /**
     * @param  UserInterface|null  $user
     * @param  bool  $everywhere
     * @return bool
     */
    public function logout(UserInterface $user = null, $everywhere = false): bool
    {
        return $this->sentinel->logout($user, $everywhere);
    }

    /**
     * @return FormRequest
     */
    public function getFormRequest(): FormRequest
    {
        return new LoginRequest();
    }

    /**
     * @return string
     */
    public function getLoginView(): string
    {
        return static::LOGIN_VIEW;
    }
}
