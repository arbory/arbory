<?php

namespace CubeSystems\Leaf\Generators;

use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Support\Collection;

class Model implements Generateable
{
    use GeneratorFormatter;

    /**
     * @var Stub
     */
    protected $stub;

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

    public function __construct( StubRegistry $stubRegistry )
    {
        $this->stub = $stubRegistry->findByName( 'admin_controller' );
        $this->fields = new Collection();
    }

    /**
     * @return bool
     */
    public function generate()
    {
        return (bool) file_put_contents(
            app_path( $this->getFilename() ),
            $this->getCompiledControllerStub()
        );
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
            $this->stub // todo : but  y
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
        return 'App\\';
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
}