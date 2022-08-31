<?php

namespace Arbory\Base\Services;

use Illuminate\Support\Collection;

class AssetPipeline
{
    /**
     * @var Collection
     */
    protected $js;

    /**
     * @var Collection
     */
    protected $css;

    /**
     * @var Collection
     */
    protected $inlineJs;

    /**
     * @var Collection
     */
    protected $inlineCss;

    public function __construct()
    {
        $this->js = new Collection();
        $this->css = new Collection();
        $this->inlineJs = new Collection();
        $this->inlineCss = new Collection();
    }

    public function js(string $filePath): void
    {
        $this->js->push($filePath);
    }

    public function css(string $filePath): void
    {
        $this->css->push($filePath);
    }

    public function prependJs(string $filePath): void
    {
        $this->js->prepend($filePath);
    }

    public function prependCss(string $filePath): void
    {
        $this->css->prepend($filePath);
    }

    public function inlineJs(string $inlineContent): void
    {
        $this->inlineJs->push($inlineContent);
    }

    public function inlineCss(string $inlineContent): void
    {
        $this->inlineCss->push($inlineContent);
    }

    public function getJs(): Collection
    {
        return $this->js;
    }

    public function getCss(): Collection
    {
        return $this->css;
    }

    public function getInlineJs(): Collection
    {
        return $this->inlineJs;
    }

    public function getInlineCss(): Collection
    {
        return $this->inlineCss;
    }
}
