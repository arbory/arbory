<?php

namespace Arbory\Base\Admin\Constructor;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Layout\Grid;
use Arbory\Base\Admin\Panels\Panel;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Layout\GridLayout;
use Arbory\Base\Admin\Layout\LazyRenderer;
use Arbory\Base\Services\FieldTypeRegistry;
use Arbory\Base\Admin\Layout\AbstractLayout;
use Arbory\Base\Admin\Layout\FormLayoutInterface;
use Arbory\Base\Admin\Layout\Transformers\AppendTransformer;

class ConstructorLayout extends AbstractLayout implements FormLayoutInterface
{
    /**
     * @var string
     */
    protected $modalUrl;

    /**
     * @var Form
     */
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
     * @var callable
     */
    protected $fieldConfigurator;

    /**
     * ConstructorLayout constructor.
     *
     * @param  string  $name
     */
    public function __construct($name = 'blocks')
    {
        $this->name = $name;

        $this->fieldConfigurator = function () {
            $this->field->setItemRenderer(new Form\Fields\Renderer\Nested\PaneledItemRenderer);
            $this->field->addClass('in-layout');
            $this->field->sortable();

            $this->field->setHidden(true);
            $this->field->setLabel('');
            $this->field->setAllowToAdd(false);
        };
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param  Form  $form
     *
     * @return FormLayoutInterface
     */
    public function setForm(Form $form): FormLayoutInterface
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Executes every time before render.
     *
     * @return mixed
     */
    public function build()
    {
        $gridLayout = new GridLayout(new Grid());
        $gridLayout->setWidth(9);
        $gridLayout->addColumn(3, new LazyRenderer([$this, 'overview']));
        $gridLayout->use(new AppendTransformer(new LazyRenderer([$this, 'renderField'])));

        $this->use($gridLayout);
    }

    /**
     * @param  mixed  $content
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
        if ($this->field === null) {
            $field = $this->form->fields()->findFieldByInputName($this->name);

            if ($field === null) {
                throw new \RuntimeException('Constructor field must be present in constructor layout');
            }

            if ($field) {
                $this->field = $field;
            } else {
                /**
                 * @var FieldTypeRegistry
                 */
                $registry = app(FieldTypeRegistry::class);

                $this->field = $registry->resolve('constructor', [$this->name]);
            }
        }

        call_user_func($this->fieldConfigurator);

        return $this->field;
    }

    /**
     * @return Panel
     */
    public function overview()
    {
        $panel = new Panel();

        $panel->setTitle('Overview');
        $panel->setContent(new Content([
            Html::div(
                Link::create($this->getModalUrl())
                    ->asButton('primary new-constructor-item')
                    ->asAjaxbox(true)
                    ->withIcon('add')
                    ->title(trans('arbory::constructor.new_block_btn'))
            )->addClass('constructor-button-wrapper'),
        ]))->addClass('overview-panel');

        return $panel;
    }

    /**
     * @return \Closure
     */
    public function renderField()
    {
        $constructor = $this->getField();

        if (! $constructor->getFieldSet()) {
            return;
        }

        $styleManager = $constructor->getFieldSet()->getStyleManager();
        $opts = $styleManager->newOptions();

        return $styleManager->render('nested', $constructor, $opts);
    }

    /**
     * @return string
     */
    public function getModalUrl()
    {
        if ($this->modalUrl) {
            return $this->modalUrl;
        }

        return $this->getForm()->getModule()->url(
            'dialog',
            [
                'dialog' => 'constructor_types',
                'field' => $this->getField()->getNameSpacedName(),
            ]
        );
    }

    /**
     * @param $url
     *
     * @return ConstructorLayout
     */
    public function setModalUrl($url): self
    {
        $this->modalUrl = $url;

        return $this;
    }
}
