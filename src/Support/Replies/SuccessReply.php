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
     */
    public function dispatch($url = '/'): JsonResponse|RedirectResponse
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
