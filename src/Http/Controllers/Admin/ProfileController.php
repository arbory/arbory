<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function twoFactor(): View
    {
        return view('arbory::controllers.profile.two-factor', [
            'user' => Sentinel::getUser(),
        ]);
    }

    public function enableTwoFactor(): View
    {
        $user = Sentinel::getUser();
        if ($user->hasTwoFactorEnabled()) {
            abort(404);
        }

        $secret = $user->createTwoFactorAuth();

        return view('arbory::controllers.profile.create-two-factor', [
            'qrCode' => $secret->toQr(),
            'uri' => $secret->toUri(),
            'string' => $secret->toString(),
        ]);
    }

    public function activateTwoFactor(Request $request): RedirectResponse|View
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $activated = Sentinel::getUser()->confirmTwoFactorAuth($request->code);

        if (! $activated) {
            return redirect()->back()->withErrors([
                'code' => __('arbory::two-factor.messages.invalid_code'),
            ]);
        }

        return view('arbory::controllers.profile.recovery-codes', [
            'user' => Sentinel::getUser(),
            'recoveryCodes' => Sentinel::getUser()->getRecoveryCodes(),
        ]);
    }

    public function disableTwoFactor(): RedirectResponse
    {
        $user = Sentinel::getUser();
        $user->disableTwoFactorAuth();

        return redirect()
            ->route('admin.users.edit', [$user->getUserId()])
            ->withSuccess(__('arbory::two-factor.messages.disabled'));
    }
}
