<?php


namespace Arbory\Base\Admin\Constructor\Blocks;


use Arbory\Base\Admin\Constructor\BlockInterface;
use Arbory\Base\Admin\Form\Fields\VirtualHasOne;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Services\AssetPipeline;

class DoubleTextBlock extends AbstractArrayBlock implements BlockInterface
{

    public function name()
    {
        return 'double_text';
    }

    public function title()
    {
        return 'Double text';
    }

    /**
     * @param FieldSet $fields
     *
     * @return mixed
     */
    public function fields(FieldSet $fields)
    {
        $fields->add(new VirtualHasOne(
            'data', function (FieldSet $fields) {


            $fields->text('a');
            $fields->text('b');
            $fields->image('c');
        }));

    }

    /**
     * @param AssetPipeline $pipeline
     *
     * @return mixed
     */
    public function assets(AssetPipeline $pipeline)
    {
        // TODO: Implement assets() method.
    }
}