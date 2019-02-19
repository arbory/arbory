<?php


namespace Arbory\Base\Admin\Layout\Transformers;


use Arbory\Base\Admin\Layout\Body;
use Arbory\Base\Admin\Layout\WrappableInterface;

/**
 *
 * Class Wrap
 *
 * @package Arbory\Base\Admin\Layout\Transformers
 */
class WrapTransformer
{
    /**
     * @var WrappableInterface
     */
    protected $wrappable;

    /**
     * Wrap constructor.
     *
     * @param WrappableInterface $wrappable
     */
    public function __construct(WrappableInterface $wrappable)
    {
        $this->wrappable = $wrappable;
    }

    public function __invoke(Body $content, callable $next)
    {
        $content->wrap(function($content) {
            $this->wrappable->setContent($content);

            return $this->wrappable->render();
        });

        return $next($content);
    }
}