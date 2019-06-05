<?php

namespace Tests\Services;

use Mockery;
use Mockery\Mock;
use Arbory\Base\Admin\Form;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Foundation\Application;
use Waavi\Translation\Models\Language;
use Arbory\Base\Admin\Form\Fields\Text;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Services\FieldSetFieldFinder;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Admin\Form\Fields\AbstractField;
use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;
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
        app()->singleton(StyleManager::class, static function () {
            $styles = [
                'normal' => \Arbory\Base\Admin\Form\Fields\Renderer\Styles\LabeledFieldStyle::class,
                'basic' => \Arbory\Base\Admin\Form\Fields\Renderer\Styles\BasicFieldStyle::class,
                'raw' => \Arbory\Base\Admin\Form\Fields\Renderer\Styles\RawFieldStyle::class,
                'nested' => \Arbory\Base\Admin\Form\Fields\Renderer\Styles\NestedFieldStyle::class,
                'section' => \Arbory\Base\Admin\Form\Fields\Renderer\Styles\SectionFieldStyle::class,
            ];

            $app = Mockery::mock(Application::class);

            return new StyleManager($app, $styles, 'normal');
        });

        $this->model = Mockery::mock(Model::class);
        $this->model->shouldReceive('translateOrNew')->andReturn($this->model);
        $this->model->shouldReceive('toArray')->andReturn([]);
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
        $text = new Text('name');

        $finder = $this->getFinderForFields(function (FieldSet $fields) use ($text) {
            $fields->add($text);
        });

        $result = $finder->find('name');

        $this->assertContainsField($result, $text);
    }

    /**
     * @test
     * @return void
     */
    public function itShouldFindNestedField()
    {
        /**
         * @var Mock|HasMany
         */
        $text = new Text('name');
        $many = $this->getHasManyField('many_names.0', $text);

        $finder = $this->getFinderForFields(function (FieldSet $fields) use ($many) {
            $fields->add($many);
        });

        $result = $finder->find('many_names.0.name');

        $this->assertContainsField($result, $many);
        $this->assertContainsField($result, $text);
    }

    /**
     * @test
     * @return void
     */
    public function itShouldFindCorrectFieldInTranslatables()
    {
        /**
         * @var Mock|Translatable
         * @var Mock|Translatable $translatable2
         */
        $text1 = new Text('foo');
        $text2 = new Text('bar');
        $translatable1 = $this->getTranslatableField('field_translation.en.foo', $text1);
        $translatable2 = $this->getTranslatableField('field_translation.en.bar', $text2);

        $finder = $this->getFinderForFields(function (FieldSet $fields) use ($translatable1, $translatable2) {
            $fields->add($translatable1);
            $fields->add($translatable2);
        });

        $result = $finder->find('field_translation.en.foo');

        $this->assertNotContains('en', $result);
        $this->assertContainsField($result, $translatable1);
        $this->assertContainsField($result, $text1);

        $result = $finder->find('field_translation.en.bar');

        $this->assertNotContains('en', $result);
        $this->assertContainsField($result, $translatable2);
        $this->assertContainsField($result, $text2);
    }

    /**
     * @param string $relationalNamespace
     * @param AbstractField $innerField
     * @return Mockery\MockInterface
     */
    private function getTranslatableField(string $relationalNamespace, AbstractField $innerField)
    {
        $mock = Mockery::mock(Translatable::class);

        $mock->shouldReceive('setFieldSet')->andReturn();
        $mock->shouldReceive('getName')->andReturn('field_translation');
        $mock->shouldReceive('getLocales')->andReturn(['en']);
        $mock->shouldReceive('getModel')->andReturn($this->model);

        $fieldSet = new FieldSet($this->model, $relationalNamespace);
        $fieldSet->add($innerField);

        $mock->shouldReceive('getLocaleFieldSet')->andReturn($fieldSet);

        return $mock;
    }

    /**
     * @param string $relationalNamespace
     * @param AbstractField $innerField
     * @return Mockery\MockInterface
     */
    private function getHasManyField(string $relationalNamespace, AbstractField $innerField)
    {
        $mock = Mockery::mock(HasMany::class);

        $mock->shouldReceive('setFieldSet')->andReturn();
        $mock->shouldReceive('getName')->andReturn('many_names');
        $mock->shouldReceive('getValue')->andReturn(new Collection([$this->model]));

        $fieldSet = new FieldSet($this->model, $relationalNamespace);
        $fieldSet->add($innerField);

        $mock->shouldReceive('getRelationFieldSet')->once()->andReturn($fieldSet);

        return $mock;
    }

    /**
     * @param array $haystack
     * @param AbstractField $field
     * @return void
     */
    private function assertContainsField(array $haystack, AbstractField $field)
    {
        $this->assertEquals($field, array_get($haystack, $field->getName()));
    }

    /**
     * @param \Closure $callback
     * @return FieldSetFieldFinder
     */
    private function getFinderForFields(\Closure $callback)
    {
        $form = new Form($this->model);
        $form->setFields($callback);

        return new FieldSetFieldFinder($this->getLanguageRepository(), $form->fields());
    }

    /**
     * @return Mockery\MockInterface|LanguageRepository
     */
    private function getLanguageRepository()
    {
        $language = Mockery::mock(Language::class);
        $language->shouldReceive('getAttribute')->andReturn($this->model);

        $languageRepository = Mockery::mock(LanguageRepository::class);
        $languageRepository->shouldReceive('all')->andReturn(new Collection([$language]));

        return $languageRepository;
    }
}
