<?php

namespace Arbory\Base\Admin\Traits;

use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Closure;

/**
 * Trait HasHighlightedText.
 */
trait HasHighlightedText
{
    protected $highlightClass = 'highlight';

    /**
     * @param string|null $highlightText
     * @return Closure
     */
    public function highlightedTextDisplay(?string $highlightText): Closure
    {
        return function ($value) use ($highlightText) {
            return Html::span($this->highlight($value, $highlightText));
        };
    }

    /**
     * @param string|null $text
     * @param string|null $highlightText
     * @return Content
     */
    public function highlight(?string $text, ?string $highlightText): Content
    {
        $content = new Content();

        if (empty($text) || empty($highlightText)) {
            return $content->push($text);
        }

        $highlightPattern = sprintf('/%s/i', preg_replace('/\s+/', '|', preg_quote($highlightText, '/')));
        $notHighlighted = preg_split($highlightPattern, $text);
        $index = 0;

        preg_replace_callback(
            $highlightPattern,
            function ($matches) use (&$content, &$index, $notHighlighted) {
                $content->push($notHighlighted[$index]);
                $content->push(Html::span($matches[0])->addClass($this->highlightClass));

                $index++;
            },
            $text
        );

        return $content->push($notHighlighted[$index]);
    }
}
