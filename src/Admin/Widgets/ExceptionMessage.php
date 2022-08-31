<?php

namespace Arbory\Base\Admin\Widgets;

use Exception;
use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class ExceptionMessage.
 */
class ExceptionMessage implements Renderable
{
    /**
     * ExceptionMessage constructor.
     */
    public function __construct(protected Exception $exception)
    {
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::div($this->exception->getMessage())->addClass('error');
    }

    /**
     * @return static
     */
    public static function create(Exception $exception)
    {
        return new static($exception);
    }
}
