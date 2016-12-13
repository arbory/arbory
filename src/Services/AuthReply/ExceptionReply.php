<?php

namespace CubeSystems\Leaf\Services\AuthReply;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\UrlGenerator;

/**
 * Class ExceptionReply
 * @package CubeSystems\Leaf\Services\AuthReply
 */
class ExceptionReply extends Reply
{
    /**
     * The recommended status code to include with the server response
     * @var integer
     */
    protected $statusCode = 500;

    /**
     * A boolean flag indicating if the manager class action was successful
     * @var boolean
     */
    protected $success = false;

    /**
     * Convert the reply to the appropriate redirect or response object
     * @var string $url
     * @return JsonResponse|RedirectResponse
     */
    public function dispatch( $url = '/' )
    {
        $request = app( 'request' );

        if( $request->ajax() || $request->wantsJson() )
        {
            return new JsonResponse( $this->toArray(), $this->statusCode );
        }

        // Should we post a flash message?
        if( $this->has( 'message' ) )
        {
            session()->flash( 'error', $this->message );
        }

        // Go to the specified url
        return redirect()->to( $this->determineRedirectUrl() )->withInput( $request->input() );
    }

    /**
     * Determine the URL we should redirect to.
     * Borrowed from Illuminate\Foundation\Validation\ValidatesRequest
     * @return string
     */
    protected function determineRedirectUrl()
    {
        if( $this->redirectUrl )
        {
            return $this->redirectUrl;
        }

        return app( UrlGenerator::class )->previous();
    }
}
