<?php

namespace Arbory\Base\Support\Replies;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

abstract class Reply implements Jsonable, Arrayable
{
    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var int
     */
    protected $statusCode = 400;

    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var Exception|null
     */
    protected $exception = null;

    /**
     * @var string|null
     */
    protected $redirectUrl = null;

    /**
     * @param string $message
     * @param array $payload
     * @param Exception $exception
     */
    public function __construct($message = '', array $payload = [], Exception $exception = null)
    {
        $this->message = $message;
        $this->payload = $payload;
        $this->exception = $exception;
    }

    /**
     * @var string
     * @return Response|Redirect
     */
    abstract public function dispatch($url = '/');

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return mixed
     */
    public function hasMessage()
    {
        return ! empty($this->message);
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function hasPayload()
    {
        return ! empty($this->payload);
    }

    /**
     * @return void
     */
    public function clearPayload()
    {
        $this->payload = [];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        if ($key === 'message') {
            return $this->hasMessage();
        }

        if ($key === 'exception') {
            return $this->hasCaughtException();
        }

        return array_key_exists($key, $this->payload);
    }

    /**
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        if ($key === 'message') {
            $this->message = '';
        }

        if ($key === 'exception') {
            $this->exception = null;
        }

        if (array_key_exists($key, $this->payload)) {
            unset($this->payload[$key]);
        }
    }

    /**
     * @return bool
     */
    public function hasCaughtException()
    {
        return $this->exception !== null;
    }

    /**
     * @param Exception $e
     * @return Exception
     */
    public function setException(Exception $e)
    {
        return $this->exception = $e;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $dispatch = [];
        $dispatch['status'] = $this->statusCode;

        if ($this->hasMessage()) {
            $dispatch['message'] = $this->message;
        }

        if ($this->hasPayload()) {
            $dispatch = array_merge($dispatch, $this->payload);
        }

        return $dispatch;
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function __get($key)
    {
        if ($key === 'message') {
            return $this->message;
        }

        if ($key === 'statusCode') {
            return $this->statusCode;
        }

        if ($key === 'exception') {
            return $this->exception;
        }

        if (array_key_exists($key, $this->payload)) {
            return $this->payload[$key];
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
