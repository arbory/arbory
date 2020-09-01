<?php

namespace Arbory\Base\Support\Replies;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class SuccessReply extends Reply
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var string
     * @return JsonResponse|RedirectResponse
     */
    public function dispatch($url = '/')
    {
        $request = app('request');

        if ($request->ajax() || $request->wantsJson()) {
            return new JsonResponse($this->toArray(), $this->statusCode);
        }

        if ($this->has('message')) {
            session()->flash('success', $this->message);
        }

        return redirect($url);
    }
}
