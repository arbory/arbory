<?php


namespace Arbory\Base\Admin\Layout\Templates;


use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Illuminate\Support\Collection;

class CrudTemplate implements TemplateInterface
{
    protected $sections = [];

    public function breadcrumbs(): Breadcrumbs
    {
        // TODO: Implement breadcrumbs() method.
    }

    public function sections(): Collection
    {
        return collect($this->sections);
    }

    public function compose(): array
    {
        return [
            'header',
            'body',
        ];
    }

    public function content( $body )
    {
        $this->sections['body'] = $body;

        return $this;
    }
}