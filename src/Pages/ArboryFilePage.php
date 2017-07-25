<?php

namespace Arbory\Base\Pages;

use Arbory\Base\Admin\Form\Fields\ArboryFile;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Database\Eloquent\Model;

class ArboryFilePage extends Model implements PageInterface
{
    /**
     * @var array
     */
    protected $fillable = [ 'arbory_file' ];

    /**
     * @param FieldSet $fieldSet
     * @return void
     */
    public function prepareFieldSet( FieldSet $fieldSet )
    {
        $fieldSet->add( new ArboryFile( 'arbory_file' ) );
    }
}
