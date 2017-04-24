<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Relation;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Str;
use ReflectionClass;

class Page extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function generate()
    {
        parent::generate();

        $this->registerPage();
    }

    /**
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function registerPage()
    {
        $className = $this->getClassName();
        $pageClassName = $this->getNamespace() . '\\' . $className;

        $stub = $this->stubRegistry->make( 'register_page', [
            'pageClassName' => $pageClassName,
            'controllerClassName' => sprintf(
                '%sHttp\Controllers\%s',
                $this->getAppNamespace(),
                $className . 'Controller'
            ),
        ] );

        $path = base_path( 'routes/web.php' );

        if( !Str::contains( $this->filesystem->get( $path ), $pageClassName ) )
        {
            $this->filesystem->append(
                $path,
                PHP_EOL . $stub
            );
        }
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $fields = $this->schema->getFields();
        $relations = $this->schema->getRelations();

        $useFields = $this->generators->getUseFields( clone $fields );
        $useRelations = $this->generators->getUseRelations( clone $relations );
        $useRelationsFields = $this->generators->getUseRelationFields( clone $relations );

        $fillable = $this->generators->getFillable( clone $fields );
        $fieldSet = $this->generators->getFieldSet( clone $fields );
        $relationMethods = $this->generators->getRelationMethods( clone $relations );
        $relationFieldSet = $this->generators->getRelationFieldSet( clone $relations );

        $use = $useFields->merge( $useRelations )->merge( $useRelationsFields );
        $fieldSet = $fieldSet->merge( $relationFieldSet );

        return $this->stubRegistry->make( 'page', [
            'namespace' => $this->getNamespace(),
            'use' => $use->implode( PHP_EOL ),
            'className' => $this->getClassName(),
            'fillable' => $this->formatter->indent( $fillable, 2 )->implode( PHP_EOL ),
            'fieldSet' => $this->formatter->indent( $fieldSet, 2 )->implode( PHP_EOL ),
            'relations' => $this->formatter->indent( $relationMethods )->implode( PHP_EOL )
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getName() ) . 'Page';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->getClassName() . '.php';
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->getAppNamespace() . 'Pages';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( 'Pages/' . $this->getFilename() );
    }
}