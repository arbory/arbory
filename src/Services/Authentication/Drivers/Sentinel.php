<?php

namespace Arbory\Base\Services\Authentication\Drivers;

use Illuminate\Support\Arr;
use Arbory\Base\Http\Requests\LoginRequest;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Foundation\Http\FormRequest;
use Cartalyst\Sentinel\Sentinel as SentinelService;
use Arbory\Base\Services\Authentication\AuthenticationMethod;

class Sentinel implements AuthenticationMethod
{
    protected const LOGIN_VIEW = 'arbory::controllers.security.login';

    public function __construct(private SentinelService $sentinel)
    {
    }

    /**
     * @param  bool  $remember
     * @param  bool  $login
     */
    public function authenticate(array $credentials, $remember = false, $login = true): bool
    {
        $user = $this->sentinel->authenticate(Arr::get($credentials, 'user', []), $remember, $login);

        return $user !== false;
    }

    /**
     * @param  UserInterface|null  $user
     * @param  bool  $everywhere
     */
    public function logout(UserInterface $user = null, $everywhere = false): bool
    {
        return $this->sentinel->logout($user, $everywhere) ? true : false;
    }

    public function getFormRequest(): FormRequest
    {
        return new LoginRequest();
    }

    public function getLoginView(): string
    {
        return static::LOGIN_VIEW;
    }
}
