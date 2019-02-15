<?php


namespace Arbory\Base\Admin\Layout\Templates;


use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Illuminate\Support\Collection;

interface TemplateInterface
{
   public function breadcrumbs():Breadcrumbs;

   public function sections():Collection;

   public function compose():array;

   public function content($content);
}