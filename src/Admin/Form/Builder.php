<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;
use Arbory\Base\Admin\Layout\WrappableInterface;

/**
 * Class Builder.
 */
class Builder implements Renderable, WrappableInterface
{
    /**
     * @var string
     */
    protected $id = 'edit-resource';

    /**
     * @var mixed
     */
    protected $content;

    /**
     * Builder constructor.
     */
    public function __construct(protected Form $form)
    {
    }

    /**
     * @param  $route
     * @param  array  $parameters
     * @return string
     */
    public function url($route, $parameters = [])
    {
        return $this->form->getModule()->url($route, $parameters);
    }

    /**
     * @return Element
     */
    protected function form()
    {
        $form = Html::form()->addAttributes([
            'id' => $this->getId(),
            'class' => 'edit-resource',
            'novalidate' => 'novalidate',
            'enctype' => 'multipart/form-data',
            'accept-charset' => 'UTF-8',
            'method' => 'post',
            'action' => $this->form->getAction(),
            'data-remote' => 'true',
            'data-remote-validation' => 'true',
            'data-type' => 'json',
        ]);

        $form->append(csrf_field());

        if ($this->form->getModel()->getKey()) {
            $form->append(Html::input()->setName('_method')->setType('hidden')->setValue('PUT'));
        }

        if ($returnUrl = $this->form->getReturnUrl()) {
            $form->append(Html::input()
                ->setName(Form::INPUT_RETURN_URL)
                ->setValue($returnUrl)
                ->setType('hidden'));
        }

        return $form;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $content = Html::div()->addClass('body');
        $content->append(new Content($this->getContent()));

        return $this->form()
            ->append($content);
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param  mixed  $content
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
