<?php

namespace Arbory\Base\Admin\Layout;

use Closure;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Panels\Panel;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Form\Widgets\Controls;
use Arbory\Base\Admin\Layout\Transformers\WrapTransformer;
use Arbory\Base\Admin\Layout\Transformers\AppendTransformer;

class PanelLayout extends AbstractLayout implements FormLayoutInterface
{
    protected $panels = [];

    /**
     * @var \SplObjectStorage
     */
    protected $fields;

    /**
     * @var Form
     */
    protected $form;

    protected $append;

    public function __construct()
    {
        $this->fields = new \SplObjectStorage();
        $this->append = new Content();
    }

    public function append($content)
    {
        $this->append->push($content);

        return $this;
    }

    /**
     * @param Closure $closure
     */
    public function setPanels(Closure $closure)
    {
        $this->panels = $closure($this);
    }

    /**
     * Add a new panel.
     *
     * @param $title
     * @param $contents
     *
     * @return Panel
     */
    public function panel($title, $contents)
    {
        $panel = new Panel();

        $panel->setTitle($title);
        $panel->setContent($contents);

        $this->panels[] = $panel;

        return $panel;
    }

    /**
     * Creates a new grid instance.
     *
     * @param callable|null $closure
     *
     * @return Grid
     */
    public function grid(?callable $closure = null)
    {
        return new Grid($closure);
    }

    /**
     * Creates a new fieldset and attaches its fields to the form.
     *
     * @param callable $closure
     * @param mixed    ...$parameters
     *
     * @return FieldSet
     */
    public function fields(callable $closure, ...$parameters): FieldSet
    {
        $fields = new FieldSet($this->form->getModel(), $this->form->fields()->getNamespace());
        $fields = $closure($fields, ...$parameters) ?: $fields;

        foreach ($fields as $field) {
            $this->fields->attach($field, $parameters);

            $this->form->fields()->add($field);
        }

        return $fields;
    }

    public function contents($content)
    {
        return new Content([
            $content,
        ]);
    }

    public function build()
    {
        // TODO: Options - 1. Remove builder from the layout, add an option disable it from transformers

        if (count($this->panels) > 0) {
            $this->setContent($this->renderPanels());

            $this->use(new WrapTransformer($this->form->getRenderer()));
            $this->use(
                new AppendTransformer(
                    new Controls(new Tools(), $this->getForm()->getModule()->url('index'))
                )
            );
        } else {
            $this->use((new Form\Layout())->setForm($this->form));
        }
    }

    public function renderPanels()
    {
        $contents = new Content();

        foreach ($this->panels as $panel) {
            $contents->push($panel->render());
        }

        return $contents;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     *
     * @return PanelLayout
     */
    public function setForm(Form $form): self
    {
        $this->form = $form;

        return $this;
    }
}
