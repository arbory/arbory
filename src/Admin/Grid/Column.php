<?php

namespace Arbory\Base\Admin\Grid;

use Closure;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class Column
 * @package Arbory\Base\Admin\Grid
 */
class Column
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $relationName;

    /**
     * @var string
     */
    protected $relationColumn;

    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Closure
     */
    protected $displayer;

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @var bool
     */
    protected $searchable = true;

    /**
     * Column constructor.
     * @param string $name
     * @param string $label
     */
    public function __construct( $name = null, $label = null )
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label ?: $this->name;
    }

    /**
     * @param Grid $grid
     * @return Column
     */
    public function setGrid( Grid $grid )
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * @param Closure $callable
     * @return Column
     */
    public function display( Closure $callable )
    {
        $this->displayer = $callable;

        return $this;
    }

    /**
     * @param bool $isSortable
     * @return Column
     */
    public function sortable( $isSortable = true )
    {
        $this->sortable = $isSortable;

        return $this;
    }

    /**
     * @param bool $isSearchable
     * @return Column
     */
    public function searchable( $isSearchable = true )
    {
        $this->searchable = $isSearchable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return $this->sortable && empty( $this->relationName );
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * @param QueryBuilder $query
     * @param $string
     * @return QueryBuilder
     */
    public function searchConditions( QueryBuilder $query, $string )
    {
        if( $this->relationName )
        {
            return $query->orWhereHas( $this->relationName, function( QueryBuilder $query ) use ( $string )
            {
                $query->where( $this->relationColumn, 'like', "$string%" );
            } );
        }

        return $query->where( $this->getName(), 'like', "$string%", 'OR' );
    }

    /**
     * @param Model $model
     * @return mixed
     */
    protected function getValue( Model $model )
    {
        if( $this->relationName )
        {
            if ( $this->relationName === 'translations' )
            {
                $translation = $model->getTranslation( null, true );

                if ( !$translation )
                {
                    return null;
                }

                return $translation->getAttribute( $this->relationColumn );
            }

            $relation = $model->getAttribute( $this->relationName );

            return ( $relation instanceof Relation )
                ? $relation->getAttribute( $this->relationColumn )
                : $relation;
        }

        return $model->getAttribute( $this->getName() );
    }

    /**
     * @param Model $model
     * @return Element
     */
    public function callDisplayCallback( Model $model )
    {
        $value = $this->getValue( $model );

        if( $this->displayer === null )
        {
            $value = (string) $value;

            if( $this->grid->hasTool( 'create' ) )
            {
                return Html::link( $value )->addAttributes( [
                    'href' => $this->grid->getModule()->url( 'edit', [ $model->getKey() ] )
                ] );
            }

            return Html::span( $value );
        }

        return call_user_func_array( $this->displayer, [ $value, $this, $model ] );
    }

    /**
     * @param $relationName
     * @param $relationColumn
     */
    public function setRelation( $relationName, $relationColumn )
    {
        $this->relationName = $relationName;
        $this->relationColumn = $relationColumn;
    }
}
