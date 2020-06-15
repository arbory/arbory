<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\HtmlString;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\IconPicker;

class IconPickerRenderer extends SelectFieldRenderer
{
    /**
     * @return Content
     */
    public function render()
    {
        $input = parent::render();

        $content = [$input, $this->getIconSelectElement()];

        return new Content($content);
    }

    /**
     * @return Element
     */
    protected function getIconSelectElement()
    {
        /** @var IconPicker $field */
        $field = $this->field;
        $items = Html::ul()->addClass('items');

        foreach ($field->getOptions()->prepend('', '') as $option) {
            $items->append(Html::li($this->getSvgIconElement($option)));
        }

        return Html::div([
            Html::div(
                $this->getSvgIconElement($field->getValue())
            )->addClass('selected'),
            $items,
        ])->addClass('contents');
    }

    /**
     * @param string $id
     * @return Element
     */
    protected function getSvgIconElement($id)
    {
        if (! $id) {
            return Html::svg();
        }

        /** @var IconPicker $field */
        $field = $this->field;
        $iconNode = $field->getIconContent($id);

        if (! $iconNode) {
            return Html::div()->addClass('element');
        }

        $node = simplexml_load_string($iconNode->asXML());
        $paths = $node->xpath('/symbol//path[@d]');

        if (! $paths) {
            return Html::div()->addClass('element');
        }

        $content = '';

        foreach ($paths as $path) {
            $content .= $path->asXML();
        }

        $attributes = $iconNode->attributes();

        $dimensions = $this->field->getDimensions();

        $width = $dimensions ? $dimensions[0] : (int) $attributes->width;
        $height = $dimensions ? $dimensions[1] : (int) $attributes->height;

        $icon = Html::span(Html::svg(new HtmlString($content))
            ->addAttributes([
                'width' => $width,
                'height' => $height,
                'viewBox' => $this->resolveViewBox($iconNode, $width, $height),
                'role' => 'presentation',
            ]))->addClass('icon');

        return Html::div([$icon, Html::span($id)])->addClass('element');
    }

    /**
     * @param \SimpleXMLElement $iconNode
     * @param  int $width
     * @param  int $height
     *
     * @return string
     */
    protected function resolveViewBox(\SimpleXMLElement $iconNode, $width, $height)
    {
        $resolver = $this->field->getViewboxResolver();

        if ($resolver) {
            return $resolver($iconNode, $width, $height);
        }

        return sprintf('0 0 %d %d', $width, $height);
    }
}
