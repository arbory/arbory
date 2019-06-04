<?php

namespace Arbory\Base\Admin\Constructor\Blocks;

use Arbory\Base\Admin\Constructor\Models\Blocks\ArrayBlock;

abstract class AbstractArrayBlock extends AbstractBlock
{
    protected $casts = [];

    /**
     * @return string
     */
    public function resource(): string
    {
        return ArrayBlock::class;
    }

    /**
     * @return string
     */
    abstract public function name();

    /**
     * @return string
     */
    abstract public function title();

    /**
     * @return array
     */
    public function casts()
    {
        return $this->casts;
    }
}
