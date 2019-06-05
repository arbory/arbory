<?php

namespace Arbory\Base\Support\Replies;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;

class ExceptionReply extends Reply
{
    /**
     * @var int
     */
    protected $statusCode = 500;

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

        return redirect()->to($this->determineRedirectUrl())->withInput($request->input());
    }

    /**
     * @return string
     */
    protected function determineRedirectUrl()
    {
        if ($this->redirectUrl) {
            return $this->redirectUrl;
        }

        return app(UrlGenerator::class)->previous();
    }
}
