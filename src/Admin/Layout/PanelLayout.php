<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Panels\FieldSetPanel;
use Arbory\Base\Admin\Panels\PanelRenderer;
use Arbory\Base\Html\Elements\Content;
use Closure;

class PanelLayout extends AbstractLayout implements LayoutInterface
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

    public function setPanels(Closure $closure)
    {
        $this->panels = $closure($this);
    }

    public function panel($name, callable $closure)
    {
        $panel = new FieldSetPanel();

        $panel->setTitle($name);

        $fields = $closure(new FieldSet($this->form->getModel(), $this->form->fields()->getNamespace()), $panel);

        $panel->setFields($fields);

        /**
         * @var FieldInterface[] $fields
         */
        foreach($fields as $field)
        {
            $this->fields->attach($field, [
                'panel' => $panel
            ]);

            $this->form->fields()->add($field);
        }

        $this->panels[] = $panel;

        return $panel;
    }

    function build()
    {
        $this->use(new SidebarLayout());

        $this->setContent($this->renderPanels());
    }

    public function renderPanels()
    {
        $contents = new Content();

        foreach($this->panels as $panel)
        {
            $contents->push((new PanelRenderer())->render($panel));
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