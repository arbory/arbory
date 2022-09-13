<?php

namespace Arbory\Base\Admin\Constructor\Blocks;

use Arbory\Base\Admin\Form\FieldSet;
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
     * @return mixed
     */
    public function fields(FieldSet $fields)
    {
        $fields->text('title');
        $fields->richtext('content');
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return 'Title & Text';
    }
}
