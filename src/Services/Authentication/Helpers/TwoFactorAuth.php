<?php

namespace Arbory\Base\Services\Authentication\Helpers;

use Illuminate\Auth\AuthManager;
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
        AuthManager $auth,
        Session     $session,
        Request     $request,
        string      $view,
        string      $sessionKey,
        bool        $useFlash,
        string      $input = '2fa_code'
    ) {
        $this->twoFactor = app(TwoFactor::class, ['input' => $input]);

        parent::__construct($auth, $session, $request, $view, $sessionKey, $useFlash, $input);
    }

    public function verify($user, array $credentials = [], bool $remember = false)
    {
        if (! $this->twoFactor->validate($user)) {
            $this->flashData($credentials, $remember);

            response()->redirectToRoute('admin.confirm.form')->throwResponse();
        }

        return true;
    }

    /**
     * Retrieve the flashed credentials in the session, and merges with the new on top.
     *
     * @param array{credentials:array, remember:bool} $credentials
     * @param mixed $remember
     * @return array
     */
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
