<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Exception;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class ExceptionMessage
 * @package Arbory\Base\Admin\Widgets
 */
class ExceptionMessage implements Renderable
{
    /**
     * @var Exception
     */
    protected $exception;

    /**
     * ExceptionMessage constructor.
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::div($this->exception->getMessage())->addClass('error');
    }

    /**
     * @param Exception $exception
     * @return static
     */
    public static function create(Exception $exception)
    {
        return new static($exception);
    }
}
