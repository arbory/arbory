<?php

namespace Arbory\Base\Admin\Layout\Transformers;

use Arbory\Base\Admin\Layout\Body;
use Arbory\Base\Admin\Layout\WrappableInterface;

/**
 * Wraps a Transformable layout to a different layout.
 *
 * Class Wrap
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

    public function __invoke(Body $body, callable $next)
    {
        $body->wrap(function ($content) {
            $this->wrappable->setContent($content);

            return $this->wrappable->render();
        });

        return $next($body);
    }
}
