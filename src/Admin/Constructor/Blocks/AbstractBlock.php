<?php


namespace Arbory\Base\Admin\Constructor\Blocks;


use Arbory\Base\Admin\Form\Fields\HasOne;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class AbstractBlock
{

    /**
     * Before block save lifecycle event
     * Note: Gets called for every block
     *
     * @param  Request  $request
     * @param  HasOne  $field
     *
     * @return void
     */
    public function beforeModelSave(Request $request, HasOne $field)
    {
        $field->beforeModelSave($request);
    }

    /**
     * After block save lifecycle event
     * Note: Gets called for every block
     *
     * @param  Request  $request
     * @param  HasOne  $field
     *
     * @return void
     */
    public function afterModelSave(Request $request, HasOne $field)
    {
        $field->afterModelSave($request);
    }
}