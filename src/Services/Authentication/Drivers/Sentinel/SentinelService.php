<?php

namespace Arbory\Base\Services\Authentication\Drivers\Sentinel;

use Arbory\Base\Services\Authentication\Helpers\TwoFactorAuth;
use Cartalyst\Sentinel\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;

class SentinelService extends Sentinel
{
    public function authenticate($credentials, bool $remember = false, bool $login = true): UserInterface|bool|null
    {
        $response = $this->fireEvent('sentinel.authenticating', [$credentials], true);

        if ($response === false) {
            return false;
        }

        if (config('two-factor.enabled', false)) {
            [$credentials, $remember] = $this->twoFactor()->getFlashedData($credentials, $remember);
        }

        if ($credentials instanceof UserInterface) {
            $user = $credentials;
        } else {
            $user = $this->users->findByCredentials($credentials);

            $valid = $user !== null && $this->users->validateCredentials($user, $credentials);
            if (! $valid) {
                $this->cycleCheckpoints('fail', $user, false);

                return false;
            }
        }

        if (! $this->twoFactor()->verify($user, $credentials, $remember)) {
            return false;
        }

        if (! $this->cycleCheckpoints('login', $user)) {
            return false;
        }

        if ($login && ! $user = $this->login($user, $remember)) {
            return false;
        }

        $this->fireEvent('sentinel.authenticated', $user);

        return $this->user = $user;
    }

    public function cycleCheckpoints(string $method, UserInterface $user = null, bool $halt = true): bool
    {
        return parent::cycleCheckpoints($method, $user, $halt);
    }

    public function fireEvent($event, $payload = [], $halt = false): mixed
    {
        return parent::fireEvent($event, $payload, $halt);
    }

    public function twoFactor(): TwoFactorAuth
    {
        return app(TwoFactorAuth::class);
    }
}
