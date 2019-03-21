<?php


namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Form\Widgets\Controls;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Layout\Transformers\AppendTransformer;
use Arbory\Base\Admin\Layout\Transformers\WrapTransformer;
use Arbory\Base\Admin\Panels\Panel;
use Arbory\Base\Html\Elements\Content;
use Illuminate\Support\Arr;
use Closure;

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

    public function __construct()
    {
        $this->fields = new \SplObjectStorage();
    }

    /**
     * @param Closure $closure
     */
    public function setPanels(Closure $closure)
    {
        $this->panels = $closure($this);
    }

    /**
     * Add a new panel
     *
     * @param $name
     * @param $contents
     *
     * @return Panel
     */
    public function panel($name, $contents)
    {
        $panel = new Panel();

        $panel->setTitle($name);
        $panel->setContent($contents);

        $this->panels[] = $panel;

        return $panel;
    }

    /**
     * Creates a new grid instance
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
     * Creates a new fieldset and attaches its fields to the form
     *
     * @param callable $closure
     * @param mixed ...$parameters
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

    /**
     * @param mixed $content
     * @return Content
     */
    public function contents($content)
    {
        return new Content(Arr::wrap($content));
    }

    /**
     * @return void
     */
    public function build()
    {
        $this->use(new WrapTransformer(new Form\Builder($this->form)));

        if (sizeof($this->panels) > 0) {
            $this->setContent($this->renderPanels());

            $this->use(
                new AppendTransformer(
                    new Controls(new Tools(), $this->getForm()->getModule()->url('index'))
                )
            );

            return;
        }

        $this->use((new Form\Layout())->setForm($this->form));
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
