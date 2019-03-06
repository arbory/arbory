<?php


namespace Arbory\Base\Admin\Constructor;


use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\FormLayoutInterface;
use Arbory\Base\Admin\Layout\LazyRenderer;
use Arbory\Base\Admin\Layout\Transformers\AppendTransformer;
use Arbory\Base\Services\FieldTypeRegistry;

class ConstructorLayout extends AbstractLayout implements FormLayoutInterface
{
    /** @var Form */
    protected $form;

    /**
     * @var Form\Fields\Constructor
     */
    protected $field;

    /**
     * @var string
     */
    protected $name;

    /**
     * ConstructorLayout constructor.
     *
     * @param string      $name
     */
    public function __construct($name = 'blocks')
    {
        $this->name = $name;
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
     */
    public function setForm(Form $form): FormLayoutInterface
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Executes every time before render
     *
     * @return mixed
     */
    function build()
    {
        // Add the field to the form
        $this->getForm()->fields()->add($this->getField());

        $this->use(new AppendTransformer(new LazyRenderer(function () {
            $constructor = $this->getField();

            if(!$constructor->getFieldSet()) {
                return;
            }

            $styleManager = $constructor->getFieldSet()->getStyleManager();
            $opts = $styleManager->newOptions();

            return $styleManager->render('nested', $constructor, $opts);
        })));
    }

    /**
     * @param mixed $content
     *
     * @return mixed
     */
    public function contents($content)
    {
        return $content;
    }

    /**
     * @return Form\Fields\Constructor
     */
    public function getField(): Form\Fields\Constructor
    {
        if($this->field === null) {
            /**
             * @var $registry FieldTypeRegistry
             */
            $registry = app(FieldTypeRegistry::class);

            $this->field = $registry->resolve("constructor", [$this->name]);
            $this->field->sortable();

            $this->field->setHidden(true);
            $this->field->setLabel('');
        }

        return $this->field;
    }
}