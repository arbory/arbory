<?php

namespace CubeSystems\Leaf\Services;

use CubeSystems\Leaf\Admin\Form\Fields\AbstractField;
use CubeSystems\Leaf\Admin\Form\Fields\HasMany;
use CubeSystems\Leaf\Admin\Form\Fields\HasOne;
use CubeSystems\Leaf\Admin\Form\Fields\Link;
use CubeSystems\Leaf\Admin\Form\Fields\Translatable;
use CubeSystems\Leaf\Admin\Form\FieldSet;
use Illuminate\Support\Collection;
use Waavi\Translation\Models\Language;
use Waavi\Translation\Repositories\LanguageRepository;

class FieldSetFieldFinder
{
    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * @var AbstractField
     */
    protected $initialField;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @param FieldSet $fieldSet
     * @param AbstractField|null $initialField
     */
    public function __construct( FieldSet $fieldSet, $initialField = null )
    {
        $this->fieldSet = $fieldSet;
        $this->initialField = $initialField;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function contains( string $attribute ): bool
    {
        $names = $this->getActualFieldNames( $attribute );

        $found = $this->find( $attribute );

        foreach( $names as $name )
        {
            if( !array_key_exists( $name, $found ) )
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $attribute
     * @return array
     */
    public function find( string $attribute )
    {
        /**
         * @var FieldSet $previousFieldSet
         * @var AbstractField $previousField
         */
        $previousFieldSet = $this->fieldSet;
        $previousField = $this->initialField;
        $fields = [];
        $inputNameParts = explode( '.', $attribute );
        
        $this->getActualFieldNames( $attribute );

        if( $this->initialField )
        {
            $fields = [ $this->initialField->getName() => $this->initialField ];
        }

        foreach( $inputNameParts as $index => $fieldName )
        {
            $field = null;

            if( $previousFieldSet )
            {
                /**
                 * @var FieldSet $previousFieldSet
                 * @var Collection $matchingFields
                 */
                $matchingFields = $previousFieldSet->getFieldsByName( $fieldName );

                if( $matchingFields && $matchingFields->count() > 0 )
                {
                    if( $matchingFields->count() === 1 )
                    {
                        $field = $matchingFields->get( 0 );
                    }
                    else
                    {
                        $field = $this->resolveMultipleFields(
                            $matchingFields, 
                            substr( $attribute, strpos( $attribute, $fieldName ) + strlen( $fieldName ) + 1, strlen( $attribute ) )
                        );
                    }
                }

                if( !$field && $previousField )
                {
                    $previousFieldSet = $this->resolveFieldSet( $previousField, $fieldName );
                }
                else
                {
                    if ( $field instanceof Link )
                    {
                        $previousFieldSet = $field->getRelationFieldSet( $previousField->getModel() );
                    }
                }

                if( $field )
                {
                    $previousField = $field;

                    $resolvedFieldSet = $this->resolveFieldSet( $previousField, $fieldName );

                    $previousFieldSet = $resolvedFieldSet ?? $previousFieldSet;

                    $fields[ $fieldName ] = $field;
                }
            }
        }

        return $fields;
    }

    /**
     * @param string $attribute
     * @return array
     */
    protected function getActualFieldNames( $attribute )
    {
        /** @var LanguageRepository $languages */
        $parts = explode( '.', $attribute );
        $languages = \App::make( LanguageRepository::class );
        $locales = $languages->all()->map( function( Language $language )
        {
            return $language->locale;
        } )->toArray();

        foreach( $parts as $index => $part )
        {
            if( is_numeric( $part ) || in_array( $part, $locales, false ) )
            {
                unset( $parts[ $index ] );
            }
        }

        return $parts;
    }

    /**
     * @param Collection $fields
     * @param string $attribute
     * @return AbstractField|null
     */
    protected function resolveMultipleFields( $fields, $attribute )
    {
        $matching = null;

        foreach( $fields->all() as $field )
        {
            /** @var AbstractField $field */
            $nameParts = explode( '.', $attribute );
            $fieldName = reset( $nameParts ) ;

            $fieldSet = $this->resolveFieldSet( $field, $fieldName );

            if ( !$fieldSet )
            {
                return null;
            }

            $finder = new self( $fieldSet, $field );

            array_shift( $nameParts );

            if( $finder->contains( implode( '.', $nameParts ) ) )
            {
                $matching = $field;
                break;
            }
        }

        return $matching;
    }

    /**
     * @param AbstractField $field
     * @param string $fieldName
     * @return FieldSet|null
     */
    protected function resolveFieldSet( AbstractField $field, string $fieldName )
    {
        if( $field instanceof HasMany )
        {
            /** @var HasMany $field */
            $nested = $field->getValue();

            if( $nested )
            {
                $resource = $nested->get( $fieldName );

                if ( !$resource )
                {
                    return null;
                }

                /**
                 * @var Collection $nested
                 * @var FieldSet $fieldSet
                 */
                return $field->getRelationFieldSet( $resource, $fieldName );
            }
        }
        elseif ( $field instanceof HasOne )
        {
            /** @var HasOne $field */
           return $field->getRelationFieldSet( $field->getValue() ?: $field->getRelatedModel() );
        }
        elseif ( $field instanceof Translatable )
        {
            /** @var Translatable $field */

            if ( !in_array( $fieldName, $field->getLocales(), true ) )
            {
                return null;
            }

            return $field->getLocaleFieldSet(
                $field->getModel()->translateOrNew( $fieldName ),
                $fieldName
            );
        }

        return null;
    }
}