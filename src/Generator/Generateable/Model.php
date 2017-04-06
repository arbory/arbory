<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
use CubeSystems\Leaf\Generator\Generateable\Extras\Structure;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Console\DetectsApplicationNamespace;

class Model extends StubGenerator implements Stubable
{
    use GeneratorFormatter, DetectsApplicationNamespace;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $timestamps;

    /**
     * @var Collection|Field
     */
    protected $fields;

    /**
     * @param StubRegistry $stubRegistry
     * @param Filesystem $filesystem
     */
    public function __construct(
        StubRegistry $stubRegistry,
        Filesystem $filesystem
    )
    {
        $this->stub = $stubRegistry->findByName( 'model' );
        $this->filesystem = $filesystem;
        $this->fields = new Collection();
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        // TODO: to camel case
        $fillable = (clone $this->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return '\'' . $field->getName() . '\',';
        } );

        $properties = (clone $this->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return 'protected $' .  $field->getName() . ';';
        } );

        $replace = [
            '{{namespace}}' => 'App', // TODO: use real
            '{{className}}' => $this->getName(),
            '{{$tableName}}' => $this->getDatabaseName(),
            '{{fillable}}' => $this->prependSpacing( $fillable, 2 )->implode( PHP_EOL ),
            '{{properties}}' => $this->prependSpacing( $properties, 1 )->implode( PHP_EOL ),
        ];

        return str_replace(
            array_keys( $replace ),
            array_values( $replace ),
            $this->stub->getContents()
        );
    }

    /**
     * @return string
     */
    public function getDatabaseName(): string
    {
        return snake_case( $this->getName() );
    }

    /**
     * @return string
     */
    public function use(): string
    {
        return 'App\\' . $this->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return ucfirst( camel_case( $this->name ) );
    }

    /**
     * @param string $name
     */
    public function setName( string $name )
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return ucfirst( camel_case( $this->name ) );
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
        return $this->getAppNamespace();
    }

    /**
     * @return bool
     */
    public function isTimestamps(): bool
    {
        return $this->timestamps;
    }

    /**
     * @param bool $timestamps
     */
    public function setTimestamps( bool $timestamps )
    {
        $this->timestamps = $timestamps;

        if ($timestamps)
        {
            // todo: make this not terrible
            $structure = new Structure();
            $field = new Field( $structure);

            $field->setName( 'created_at' );
            $field->setType( Hidden::class );
            $structure->setType( 'timestamp' );

            $this->addField( $field );

            $structure = new Structure();
            $field = new Field( $structure);

            $field->setName( 'updated_at' );
            $field->setType( Hidden::class );
            $structure->setType( 'timestamp' );

            $this->addField( $field );
        }
    }

    /**
     * @return Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param Field $field
     * @return void
     */
    public function addField( Field $field )
    {
        $this->fields->push( $field );
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( $this->getFilename() );
    }
}