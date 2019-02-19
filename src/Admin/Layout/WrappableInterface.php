<?php


namespace Arbory\Base\Admin\Layout;


interface WrappableInterface
{
    public function setContent($content);
    public function getContent();

    public function render();
}