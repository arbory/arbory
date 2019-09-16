<?php

namespace Tests\Html\Elements;

use PHPUnit\Framework\TestCase;
use Arbory\Base\Html\Elements\Element;

final class ElementTest extends TestCase
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->name = 'test';
        $this->attributes = [
            'name' => 'field_name',
            'data-length' => '10',
        ];
    }

    /**
     * @test
     * @return void
     */
    public function itShouldAddClass()
    {
        $element = $this->getElement();

        $element->addClass('box');
        $element->addClass('bright_green');

        $this->assertEquals('box bright_green', array_get($element->attributes()->toArray(), 'class'));
    }

    /**
     * @test
     * @return void
     */
    public function itShouldAppendElementToContents()
    {
        $existingElement = $this->getElement();
        $newElement = $this->getElement();
        $element = $this->getElement([$existingElement]);

        $element->append($newElement);

        $content = $element->content();

        $this->assertEquals(2, $content->count());
        $this->assertEquals($existingElement, $content->get(0));
        $this->assertEquals($newElement, $content->get(1));
    }

    /**
     * @test
     * @return void
     */
    public function itShouldPrependElementToContents()
    {
        $existingElement = $this->getElement();
        $newElement = $this->getElement();
        $element = $this->getElement([$existingElement]);

        $element->prepend($newElement);

        $content = $element->content();

        $this->assertEquals(2, $content->count());
        $this->assertEquals($newElement, $content->get(0));
        $this->assertEquals($existingElement, $content->get(1));
    }

    /**
     * @test
     * @return void
     */
    public function itShouldHaveAttributes()
    {
        $element = $this->getElement();

        $element->addAttributes($this->attributes);

        $this->assertEquals($this->attributes, $element->attributes()->toArray());
    }

    /**
     * @test
     * @return void
     */
    public function itShouldFormatNameWithMultipleValues()
    {
        $supportedEndingFormats = ['[]', '[ ]'];

        foreach ($supportedEndingFormats as $ending) {
            $this->assertEquals('resources[field_name][]', Element::formatName('resources.field_name'.$ending));
        }
    }

    /**
     * @param array|null $content
     * @return Element
     */
    private function getElement(array $content = null)
    {
        return new Element(uniqid('', true), $content);
    }
}
