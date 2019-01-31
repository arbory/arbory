<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Admin\Form\Fields\AbstractField;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Sortable;
use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Services\FieldSetFieldFinder;
use Arbory\Base\Services\FieldTypeRegistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Waavi\Translation\Repositories\LanguageRepository;

/**
 * Class FieldSet
 * @package Arbory\Base\Admin\Form
 * @method \Arbory\Base\Admin\Form\Fields\BelongsTo belongsTo( string $relationName )
 * @method \Arbory\Base\Admin\Form\Fields\BelongsToMany belongsToMany( string $relationName )
 * @method \Arbory\Base\Admin\Form\Fields\Boolean boolean( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\Checkbox checkbox( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\DateTime dateTime( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\ArboryFile file( string $relationName )
 * @method \Arbory\Base\Admin\Form\Fields\HasMany hasMany( string $relationName, \Closure $fieldSetCallback )
 * @method \Arbory\Base\Admin\Form\Fields\HasOne hasOne( string $relationName, \Closure $fieldSetCallback )
 * @method \Arbory\Base\Admin\Form\Fields\Hidden hidden( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\SpriteIcon icon( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\ArboryImage image( string $relationName )
 * @method \Arbory\Base\Admin\Form\Fields\Link link( string $relationName )
 * @method \Arbory\Base\Admin\Form\Fields\MapCoordinates map( string $relationName )
 * @method \Arbory\Base\Admin\Form\Fields\CompactRichtext markup( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\MultipleSelect multipleSelect( string $relationName )
 * @method \Arbory\Base\Admin\Form\Fields\ObjectRelation objectRelation( string $relationName, $relatedModelTypeOrCollection, int $limit = 0 )
 * @method \Arbory\Base\Admin\Form\Fields\Password password( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\Richtext richtext( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\Select select( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\Slug slug( string $fieldName, string $fromFieldName, string $apiUrl )
 * @method \Arbory\Base\Admin\Form\Fields\Sortable sortable( string $fieldName, \Arbory\Base\Admin\Form\Fields\HasMany $field )
 * @method \Arbory\Base\Admin\Form\Fields\Text text( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\Textarea textarea( string $fieldName )
 * @method \Arbory\Base\Admin\Form\Fields\Translatable translatable( FieldInterface $field )
 */
class FieldSet extends Collection
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var FieldTypeRegistry
     */
    protected $fieldTypeRegister;

    /**
     * @var StyleManager
     */
    protected $styleManager;


    /**
     * Resource constructor.
     *
     * @param Model        $model
     * @param string       $namespace
     * @param StyleManager $styleManager
     */
    public function __construct( Model $model, $namespace, StyleManager $styleManager = null )
    {
        if(is_null($styleManager)) {
            $styleManager = app(StyleManager::class);
        }

        $this->namespace = $namespace;
        $this->model = $model;
        $this->fieldTypeRegister = app(FieldTypeRegistry::class);
        $this->styleManager = $styleManager;

        parent::__construct( [] );
    }

    /**
     * @param string $inputName
     * @return AbstractField|null
     */
    public function findFieldByInputName( string $inputName )
    {
        $inputNameParts = explode( '.', $inputName );
        $fields = $this->findFieldsByInputName( $inputName );

        return array_get( $fields, end( $inputNameParts ) );
    }

    /**
     * @param string $inputName
     * @return array
     */
    public function findFieldsByInputName( string $inputName )
    {
        return ( new FieldSetFieldFinder( app( LanguageRepository::class ), $this ) )->find( $inputName );
    }

    /**
     * @param string $fieldName
     * @return AbstractField|null
     */
    public function getFieldByName( string $fieldName )
    {
        return $this->getFields()->first( function( AbstractField $field ) use ( $fieldName )
        {
            return $field->getName() === $fieldName;
        } );
    }

    /**
     * @param string $fieldName
     * @return Collection
     */
    public function getFieldsByName( string $fieldName )
    {
        $fields = [];

        foreach( $this->getFields()->toArray() as $field )
        {
            /** @var AbstractField $field */

            if( $field->getName() === $fieldName )
            {
                $fields[] = $field;
            }

            if( $field instanceof Sortable && $field->getField()->getName() === $fieldName )
            {
                $fields[] = $field->getSortableField();
            }
        }

        return new Collection( $fields );
    }

    /**
     * @return Collection|FieldInterface[]
     */
    public function getFields()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     *
     */
    public function getRules()
    {
        $rules = [];

        foreach( $this->all() as $field )
        {
            $rules = array_merge( $rules, $field->getRules() );
        }

        return $rules;
    }

    /**
     * @param FieldInterface $field
     * @param null $key
     * @return FieldSet|Collection
     */
    public function prepend( $field, $key = null )
    {
        $field->setFieldSet( $this );

        return parent::prepend( $field, $key );
    }

    /**
     * @param FieldInterface $field
     * @return FieldInterface
     */
    public function add( FieldInterface $field )
    {
        $this->push( $field );

        return $field;
    }

    /**
     * @param string $key
     * @param FieldInterface $field
     */
    public function offsetSet( $key, $field )
    {
        $field->setFieldSet( $this );

        parent::offsetSet( $key, $field );
    }

    /**
     * Renders fieldSet with defined renderer
     *
     * @return Content
     */
    public function render()
    {
        $content      = new Content();
        $styleManager = $this->getStyleManager();
        $defaultStyle = $styleManager->getDefaultStyle();

        foreach ( $this->all() as $field ) {
            $style = $field->getStyle() ?: $defaultStyle;

            $content->push(
                $styleManager->make($style, $field)
            );
        }

        return $content;
    }

    /**
     * @return array|FieldInterface[]
     */
    public function all()
    {
        return parent::all();
    }

    /**
     * @return StyleManager
     */
    public function getStyleManager(): StyleManager
    {
        return $this->styleManager;
    }

    /**
     * @param StyleManager $styleManager
     *
     * @return FieldSet
     */
    public function setStyleManager( StyleManager $styleManager ): self
    {
        $this->styleManager = $styleManager;

        return $this;
    }

    /**
     * @param string $method
     * @param array  $parameters
     *
     * @return FieldInterface|mixed
     */
    public function __call($method, $parameters)
    {
        $fieldTypeClass = $this->fieldTypeRegister->findByType($method);

        if (class_exists($fieldTypeClass)) {
            return $this->add(new $fieldTypeClass(...$parameters));
        }

        return parent::__call($method, $parameters);
    }
}
