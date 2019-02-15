<?php


namespace Arbory\Base\Admin\Layout\Templates;


class TemplateRenderer
{
    public function render($templateClass, array $sections = [])
    {
        if(is_string($templateClass)) {
            $template = app()->make($templateClass);
        } else if($templateClass instanceof TemplateInterface) {
            $template = $templateClass;
        } else {
            throw new \InvalidArgumentException("Invalid template passed");
        }

        $composition = $template->compose();

        return $this->renderChild($template, $composition);
    }

    // TODO: Proper nesting
    protected function renderChild(TemplateInterface $template, $composition) {
        $output= [];

        foreach($composition as $key => $value) {
            $parent = null;
            $children = [];

            // TODO: allow any iterable
            if(is_array($value)) {
                $children = $this->renderChild($template, $value);

                $parent = $key;
            } else if(is_string($key) && is_string($value)) {
                $parent = $key;

                $children = $this->renderChild($template, [$value]);
            } else {
                $output[] = $this->renderSection($value, $template);
            }

            if($parent) {
                $output[] =  $this->renderSection($template, $parent,
                $this->renderChild($template, $children)
                    );
            }
        }

        return $output;
    }

    protected function renderSection(TemplateInterface $template, $section, $inner = null)
    {
        $sections = $template->sections();

        if(array_key_exists($section, $sections)) {
            $section = $sections[$section];

            return $section($inner);
        } else {
            throw new \InvalidArgumentException("Unknown section '{$section}'");
        }
    }
}