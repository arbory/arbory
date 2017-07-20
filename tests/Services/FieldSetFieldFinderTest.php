<?php

namespace Tests\Services;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\AbstractField;
use CubeSystems\Leaf\Admin\Form\Fields\HasMany;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Form\Fields\Translatable;
use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Services\FieldSetFieldFinder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Waavi\Translation\Models\Language;
use Waavi\Translation\Repositories\LanguageRepository;

final class FieldSetFieldFinderTest extends TestCase
{
    /**
     * @var Mock|Model
     */
    private $model;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->model = Mockery::mock( Model::class );
        $this->model->shouldReceive( 'translateOrNew' )->andReturn( $this->model );
        $this->model->shouldReceive( 'toArray' )->andReturn( [] );
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     * @return void
     */
    public function itShouldFindFieldInRoot()
    {
        $text = new Text( 'name' );

        $finder = $this->getFinderForFields( function( Form $form ) use ( $text )
        {
            $form->addField( $text );
        } );

        $result = $finder->find( 'name' );

        $this->assertContainsField( $result, $text );
    }

    /**
     * @test
     * @return void
     */
    public function itShouldFindNestedField()
    {
        /**
         * @var Mock|HasMany $many
         */
        $text = new Text( 'name' );
        $many = $this->getHasManyField( 'many_names.0', $text );

        $finder = $this->getFinderForFields( function( Form $form ) use ( $many )
        {
            $form->addField( $many );
        } );

        $result = $finder->find( 'many_names.0.name' );

        $this->assertContainsField( $result, $many );
        $this->assertContainsField( $result, $text );
    }

    /**
     * @test
     * @return void
     */
    public function itShouldFindCorrectFieldInTranslatables()
    {
        /**
         * @var Mock|Translatable $translatable1
         * @var Mock|Translatable $translatable2
         */
        $text1 = new Text( 'foo' );
        $text2 = new Text( 'bar' );
        $translatable1 = $this->getTranslatableField( 'field_translation.en.foo', $text1 );
        $translatable2 = $this->getTranslatableField( 'field_translation.en.bar', $text2 );

        $finder = $this->getFinderForFields( function( Form $form ) use ( $translatable1, $translatable2 )
        {
            $form->addField( $translatable1 );
            $form->addField( $translatable2 );
        } );

        $result = $finder->find( 'field_translation.en.foo' );

        $this->assertNotContains( 'en', $result );
        $this->assertContainsField( $result, $translatable1 );
        $this->assertContainsField( $result, $text1 );

        $result = $finder->find( 'field_translation.en.bar' );

        $this->assertNotContains( 'en', $result );
        $this->assertContainsField( $result, $translatable2 );
        $this->assertContainsField( $result, $text2 );
    }

    /**
     * @param string $relationalNamespace
     * @param AbstractField $innerField
     * @return Mockery\MockInterface
     */
    private function getTranslatableField( string $relationalNamespace, AbstractField $innerField )
    {
        $mock = Mockery::mock( Translatable::class );

        $mock->shouldReceive( 'setFieldSet' )->andReturn();
        $mock->shouldReceive( 'getName' )->andReturn( 'field_translation' );
        $mock->shouldReceive( 'getLocales' )->andReturn( [ 'en' ] );
        $mock->shouldReceive( 'getModel' )->andReturn( $this->model );

        $fieldSet = new FieldSet( $this->model, $relationalNamespace );
        $fieldSet->add( $innerField );

        $mock->shouldReceive( 'getLocaleFieldSet' )->andReturn( $fieldSet );

        return $mock;
    }

    /**
     * @param string $relationalNamespace
     * @param AbstractField $innerField
     * @return Mockery\MockInterface
     */
    private function getHasManyField( string $relationalNamespace, AbstractField $innerField )
    {
        $mock = Mockery::mock( HasMany::class );

        $mock->shouldReceive( 'setFieldSet' )->andReturn();
        $mock->shouldReceive( 'getName' )->andReturn( 'many_names' );
        $mock->shouldReceive( 'getValue' )->andReturn( new Collection( [ $this->model ] ) );

        $fieldSet = new FieldSet( $this->model, $relationalNamespace );
        $fieldSet->add( $innerField );

        $mock->shouldReceive( 'getRelationFieldSet' )->once()->andReturn( $fieldSet );

        return $mock;
    }

    /**
     * @param array $haystack
     * @param AbstractField $field
     * @return void
     */
    private function assertContainsField( array $haystack, AbstractField $field )
    {
        $this->assertEquals( $field, array_get( $haystack, $field->getName() ) );
    }

    /**
     * @param \Closure $callback
     * @return FieldSetFieldFinder
     */
    private function getFinderForFields( \Closure $callback )
    {
        return new FieldSetFieldFinder( $this->getLanguageRepository(), ( new Form( $this->model, $callback ) )->fields() );
    }

    /**
     * @return Mockery\MockInterface|LanguageRepository
     */
    private function getLanguageRepository()
    {
        $language = Mockery::mock( Language::class);
        $language->shouldReceive( 'getAttribute' )->andReturn( $this->model );

        $languageRepository = Mockery::mock( LanguageRepository::class );
        $languageRepository->shouldReceive( 'all' )->andReturn( new Collection( [ $language ] ) );

        return $languageRepository;
    }
}