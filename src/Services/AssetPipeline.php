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
     * @return void
     */
    public function js(string $filePath): void
    {
        $this->js->push($filePath);
    }

    /**
     * @param string $filePath
     * @return void
     */
    public function css(string $filePath): void
    {
        $this->css->push($filePath);
    }

    /**
     * @param string $filePath
     * @return void
     */
    public function prependJs(string $filePath): void
    {
        $this->js->prepend($filePath);
    }

    /**
     * @param string $filePath
     * @return void
     */
    public function prependCss(string $filePath): void
    {
        $this->css->prepend($filePath);
    }

    /**
     * @param string $inlineContent
     */
    public function inlineJs(string $inlineContent): void
    {
        $this->inlineJs->push($inlineContent);
    }

    /**
     * @param string $inlineContent
     */
    public function inlineCss(string $inlineContent): void
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

    /**
     * @param  string  $path
     *
     * @return string
     */
    public function getStaticUrl(string $path): string
    {
        return asset(
            config('arbory.assets.directory') . '/' . ltrim($path, '/')
        );
    }

    /**
     * @param  string  $path
     *
     * @return string
     * @throws \Exception
     */
    public function getMixUrl(string $path): string
    {
        return asset(
            mix($path, config('arbory.assets.directory'))
        );
    }
}
