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

    /**
     * @param string $filePath
     */
    public function js(string $filePath)
    {
        $this->js->push($filePath);
    }

    /**
     * @param string $filePath
     */
    public function css(string $filePath)
    {
        $this->css->push($filePath);
    }

    /**
     * @param string $inlineContent
     */
    public function inlineJs(string $inlineContent)
    {
        $this->inlineJs->push($inlineContent);
    }

    /**
     * @param string $inlineContent
     */
    public function inlineCss(string $inlineContent)
    {
        $this->inlineCss->push($inlineContent);
    }

    /**
     * @return Collection
     */
    public function getJs(): Collection
    {
        return $this->js;
    }

    /**
     * @return Collection
     */
    public function getCss(): Collection
    {
        return $this->css;
    }

    /**
     * @return Collection
     */
    public function getInlineJs(): Collection
    {
        return $this->inlineJs;
    }

    /**
     * @return Collection
     */
    public function getInlineCss(): Collection
    {
        return $this->inlineCss;
    }
}
