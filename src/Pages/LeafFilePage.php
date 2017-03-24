<?php

namespace CubeSystems\Leaf\Pages;

use CubeSystems\Leaf\Admin\Form\Fields\LeafFile;
use CubeSystems\Leaf\Admin\Form\FieldSet;
use Illuminate\Database\Eloquent\Model;

class LeafFilePage extends Model implements PageInterface
{
    /**
     * @var array
     */
    protected $fillable = [ 'leaf_file' ];

    /**
     * @param FieldSet $fieldSet
     * @return void
     */
    public function prepareFieldSet( FieldSet $fieldSet )
    {
        $fieldSet->add( new LeafFile( 'leaf_file' ) );
    }
}
