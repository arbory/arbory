<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Admin\Form\Fields\Renderer\TranslatableFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;
use Dimsav\Translatable\Translatable as TranslatableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Translatable
 * @package CubeSystems\Leaf\Field
 */
class Translatable extends AbstractField
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var string
     */
    protected $currentLocale;

    /**
     * Translatable constructor.
     * @param FieldInterface $field
     */
    public function __construct( FieldInterface $field )
    {
        $this->field = $field;
        $this->locales = (array) config( 'translatable.locales' );
        $this->currentLocale = request()->getLocale();

        parent::__construct( 'translations' );
    }

    /**
     * @return TranslatableModel|Model
     */
    public function getModel()
    {
        return parent::getModel();
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * @return Element|string
     */
    public function render()
    {
        return ( new TranslatableFieldRenderer( $this ) )->render();
    }

    /**
     * @param $locale
     * @return FieldSet
     */
    public function getTranslatableResource( $locale )
    {
        $fieldSet = new FieldSet(
            $this->getModel()->translateOrNew( $locale ),
            $this->getNameSpacedName() . '.' . $locale
        );

        $fieldSet->push( clone $this->field );

        return $fieldSet;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        foreach( $this->locales as $locale )
        {
            foreach( $this->getTranslatableResource( $locale )->getFields() as $field )
            {
                $field->beforeModelSave( $request );
            }
        }
    }

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {
        foreach( $this->locales as $locale )
        {
            foreach( $this->getTranslatableResource( $locale )->getFields() as $field )
            {
                $field->afterModelSave( $request );
            }
        }
    }
}
