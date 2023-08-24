<?php

namespace Arbory\Base\Http\Middleware;

use Arbory\Base\Admin\Module;
use Arbory\Base\Support\Facades\Admin;
use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ArboryAdminGuestMiddleware.
 */
class ArboryAdminGuestMiddleware
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * ArboryAdminGuestMiddleware constructor.
     *
     * @param $sentinel
     */
    public function __construct(Sentinel $sentinel)
    {
        $this->sentinel = $sentinel;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return JsonResponse|RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if ($this->sentinel->check()) {
            if ($request->ajax()) {
                $message = trans('arbory.admin_unauthorized', 'Unauthorized');

                return response()->json(['error' => $message], 401);
            } else {
                $firstAvailableModule = $this->getFirstAvailableModule();

                if (! $firstAvailableModule) {
                    throw new AccessDeniedHttpException();
                }

                return redirect($firstAvailableModule->url('index'));
            }
        }

        return $next($request);
    }

    /**
     * @return Module|null
     */
    private function getFirstAvailableModule(): ?Module
    {
        $menuOrder = array_reverse(Arr::flatten(config('arbory.menu')));

        return Admin::modules()
            ->sortByDesc(fn (Module $module) => array_search($module->getControllerClass(), $menuOrder))
            ->first(fn (Module $module) => $module->isAuthorized());
    }
}
