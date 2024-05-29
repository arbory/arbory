<?php

namespace Arbory\Base\Services\Authentication\Helpers;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Session\EncryptedStore;
use Illuminate\Support\Facades\Crypt;
use Laragear\TwoFactor\TwoFactor;
use Laragear\TwoFactor\TwoFactorLoginHelper;

class TwoFactorAuth extends TwoFactorLoginHelper
{
    protected TwoFactor $twoFactor;

    public function __construct(
        protected AuthManager $auth,
        protected Session $session,
        protected Request $request,
        protected string $view,
        protected string $sessionKey,
        protected bool $useFlash,
        protected string $input = '2fa_code',
        protected string $redirect = '',
    ) {
        $this->twoFactor = app(TwoFactor::class, ['input' => $input, 'safeDeviceInput' => 'safe_device']);

        parent::__construct($auth, $session, $request, $view, $sessionKey, $useFlash, $input, $redirect);
    }

    public function verify($user, array $credentials = [], bool $remember = false): bool
    {
        if (! $this->twoFactor->validate($user)) {
            $this->flashData($credentials, $remember);

            response()->redirectToRoute('admin.confirm.form')->throwResponse();
        }

        return true;
    }

    public function getFlashedData(array $credentials, mixed $remember): array
    {
        $original = $this->session->pull("$this->sessionKey.credentials", []);
        $remember = $this->session->pull("$this->sessionKey.remember", $remember);

        // If the session is not encrypted, we will need to decrypt the credentials manually.
        if (! $this->session instanceof EncryptedStore) {
            foreach ($original as $index => $value) {
                $original[$index] = Crypt::decryptString($value);
            }
        }

        return [array_merge($original, $credentials), $remember];
    }
}
