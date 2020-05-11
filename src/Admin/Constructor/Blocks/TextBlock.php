<?php

namespace Arbory\Base\Admin\Constructor\Blocks;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Services\AssetPipeline;
use Arbory\Base\Admin\Constructor\BlockInterface;
use Arbory\Base\Admin\Constructor\Models\Blocks\TextBlock as TextBlockModel;

class TextBlock extends AbstractBlock implements BlockInterface
{
    /**
     * @return string
     */
    public function name()
    {
        return 'text_block';
    }

    /**
     * @return string
     */
    public function resource(): string
    {
        return TextBlockModel::class;
    }

    /**
     * @param  FieldSet  $fields
     *
     * @return mixed
     */
    public function fields(FieldSet $fields)
    {
        $fields->text('title');
        $fields->richtext('content');
    }

    /**
     * @param  AssetPipeline  $pipeline
     *
     * @return mixed
     */
    public function assets(AssetPipeline $pipeline)
    {
        // TODO: Implement assets() method.
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return 'Title & Text';
    }
}
