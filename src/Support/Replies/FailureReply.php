<?php

namespace Arbory\Base\Support\Replies;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class FailureReply extends Reply
{
    /**
     * @var int
     */
    protected $statusCode = 422;

    /**
     * @var bool
     */
    protected $success = false;

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
            session()->flash('error', $this->message);
        }

        return redirect($url);
    }
}
