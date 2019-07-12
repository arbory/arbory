<?php

namespace Arbory\Base\Admin\Constructor;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Services\AssetPipeline;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Form\Fields\HasOne;

interface BlockInterface
{
    /**
     * Handles all before save events for any fields, overwriting it means you'll have to call beforeSave for $field.
     *
     * @param  Request  $request
     * @param  HasOne  $field
     */
    public function beforeModelSave(Request $request, HasOne $field);

    /**
     * Handles all after save events for any fields, overwriting it means you'll have to call afterSave for $field.
     *
     * @param  Request  $request
     * @param  HasOne  $field
     */
    public function afterModelSave(Request $request, HasOne $field);

    /**
     * Human readable title for field.
     *
     * @return string
     */
    public function title();

    /**
     * Unique name for this field.
     *
     * @return string
     */
    public function name();

    /**
     * Model name as string.
     *
     * @return string
     */
    public function resource(): string;

    /**
     * Defined fields for this block.
     *
     * @param  FieldSet  $fields
     */
    public function fields(FieldSet $fields);

    /**
     * @param  AssetPipeline  $pipeline
     */
    public function assets(AssetPipeline $pipeline);
}
