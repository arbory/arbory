<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Structure;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use DateTimeImmutable;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Migration extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        return $this->stubRegistry->make( 'migration', [
            'className' => $this->getClassName(),
            'modelTableName' => Str::snake( $this->schema->getNamePlural() ),
            'pageTableName' => Str::snake( $this->schema->getNameSingular() ),
            'modelSchemaCreate' => $this->getCompiledModelSchema(),
            'pageSchemaCreate' => $this->getCompiledPageSchema(),
            'insertMenuItem' => $this->getCompiledInsertMenuItem(),
            'schemaDown' => $this->getCompiledDropSchemas(),
            'menuItemDown' => $this->getCompiledDownMenuItem()
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return 'Create' . $this->formatter->className( $this->schema->getNamePlural() ) . 'Table';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        $time = new DateTimeImmutable();

        return sprintf(
            '%s_create_%s_table.php',
            $time->format( 'Y_m_d_His' ),
            snake_case( $this->schema->getNamePlural() )
        );
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return (string) null;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return base_path( 'database/migrations/' . $this->getFilename() );
    }

    /**
     * @param Structure $structure
     * @return string
     */
    protected function buildSecondArgument( Structure $structure )
    {
        $argument = null;

        if( $structure->getType() === 'integer' )
        {
            $argument = $structure->isAutoIncrement();
        }
        elseif( $structure->getType() === 'string' )
        {
            $argument = $structure->getLength();
        }

        return $argument ? ', ' . $argument : '';
    }

    /**
     * @param Structure $structure
     * @return string
     */
    protected function buildColumn( Structure $structure )
    {
        $builder = '';
        $defaultValue = $structure->getDefaultValue();

        if( $structure->isNullable() )
        {
            $builder .= '->nullable()';
        }

        if( $defaultValue )
        {
            $builder .= '->default( \'' . $defaultValue . '\' )';
        }

        return $builder;
    }

    /**
     * @return Collection
     */
    protected function getCommonSchemaFields(): Collection
    {
        $fields = new Collection();

        if( $this->schema->usesId() )
        {
            $fields->push( '$table->increments( \'id\' );' );
        }

        if( $this->schema->usesTimestamps() )
        {
            $fields->push( '$table->timestamps();' );
        }

        return $fields;
    }

    /**
     * @return string
     */
    protected function getCompiledModelSchema(): string
    {
        $fields = $this->getCommonSchemaFields();

        if( !$this->selectGeneratables->contains( Model::class ) )
        {
            return (string) null;
        }

        $fields = $fields->merge( $this->schema->getFields()->map( function( Field $field )
        {
            $structure = $field->getStructure();

            return sprintf(
                '$table->%s( \'%s\'%s )%s;',
                $structure->getType(),
                $field->getDatabaseName(),
                $this->buildSecondArgument( $structure ),
                $this->buildColumn( $structure )
            );
        } ) );

        $compiled = $this->stubRegistry->make( 'parts.schema_create', [
            'tableName' => $this->getModelTableName(),
            'schemaField' => $this->formatter->indent( $fields->implode( PHP_EOL ), 1 ),
        ] );

        return $this->formatter->indent( $compiled, 2 );
    }

    /**
     * @return string
     */
    protected function getCompiledPageSchema(): string
    {
        $fields = $this->getCommonSchemaFields();

        if( !$this->selectGeneratables->contains( Page::class ) )
        {
            return (string) null;
        }

        if( !$this->selectGeneratables->contains( Model::class ) )
        {
            $fields = $fields->merge( $this->schema->getFields()->map( function( Field $field )
            {
                $structure = $field->getStructure();

                return sprintf(
                    '$table->%s( \'%s\'%s )%s;',
                    $structure->getType(),
                    $field->getDatabaseName(),
                    $this->buildSecondArgument( $structure ),
                    $this->buildColumn( $structure )
                );
            } ) );
        }

        $compiled = $this->stubRegistry->make( 'parts.schema_create', [
            'tableName' => $this->getPageTableName(),
            'schemaField' => $this->formatter->indent( $fields->implode( PHP_EOL ), 1 ),
        ] );

        return
            str_repeat( PHP_EOL, 2 ) .
            str_repeat( "\t", 2 ) .
            $this->formatter->indent( $compiled, 2 );
    }

    /**
     * @return string
     */
    protected function getCompiledInsertMenuItem(): string
    {
        if( !$this->selectGeneratables->contains( AdminController::class ) )
        {
            return (string) null;
        }

        $compiled = $this->stubRegistry->make( 'parts.insert_admin_menu_item', [
            'title' => ucfirst( $this->schema->getNamePlural() ),
            'controllerClass' =>
                $this->getAppNamespace() . 'Http\Controllers\Admin\\' .
                $this->formatter->className( $this->schema->getNameSingular() ) . 'Controller',
        ] );

        return
            str_repeat( PHP_EOL, 2 ) .
            str_repeat( "\t", 2 ) .
            $this->formatter->indent( $compiled, 2 );
    }

    /**
     * @return string
     */
    protected function getCompiledDropSchemas(): string
    {
        $items = new Collection();

        if( $this->selectGeneratables->contains( Model::class ) )
        {
            $items->push( 'Schema::dropIfExists( \'' . $this->getModelTableName() . '\' );' );
        }

        if( $this->selectGeneratables->contains( Page::class ) )
        {
            $items->push( 'Schema::dropIfExists( \'' . $this->getPageTableName() . '\' );' );
        }

        return $this->formatter->indent( $items->implode( PHP_EOL ), 2 );
    }

    /**
     * @return string
     */
    protected function getCompiledDownMenuItem(): string
    {
        if( !$this->selectGeneratables->contains( AdminController::class ) )
        {
            return (string) null;
        }

        $compiled = $this->stubRegistry->make( 'parts.delete_admin_menu_item', [
            'title' => ucfirst( $this->schema->getNamePlural() ),
            'controllerClass' =>
                $this->getAppNamespace() . 'Http\Controllers\Admin\\' .
                $this->formatter->className( $this->schema->getNameSingular() ) . 'Controller',
        ] );

        return
            str_repeat( PHP_EOL, 2 ) .
            str_repeat( "\t", 2 ) .
            $this->formatter->indent( $compiled, 2 );
    }

    /**
     * @return string
     */
    protected function getModelTableName(): string
    {
        return Str::snake( $this->schema->getNamePlural() );
    }

    /**
     * @return string
     */
    protected function getPageTableName(): string
    {
        return Str::snake( $this->schema->getNameSingular() ) . '_pages';
    }
}