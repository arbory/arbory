<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\IsTranslatable;
use Arbory\Base\Admin\Form\Fields\Renderer\InputGroupRenderer;
use Arbory\Base\Admin\Form\Fields\Renderer\InputGroupRendererInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class AbstractField
 * @package Arbory\Base\Admin\Form\Fields
 */
abstract class AbstractField implements FieldInterface
{
    use IsTranslatable;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     * @var bool
     */
    protected $readOnly = false;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var Renderable
     */
    protected $renderer;

    /**
     * @var Content
     */
    protected $infoBlock;

    /**
     * @var int
     */
    protected $rows;

    /**
     * @var string
     */
    protected $style;

    /**
     * AbstractField constructor.
     * @param string $name
     */
    public function __construct( $name )
    {
        $this->setName( $name );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameSpacedName()
    {
        return implode( '.', [
            $this->getFieldSet()->getNamespace(),
            $this->getName()
        ] );
    }

    /**
     * @return string
     */
    public function getFieldTypeName()
    {
        return 'type-' . camel_case(class_basename(static::class));
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if( $this->value === null )
        {
            $this->value = $this->getModel()->getAttribute( $this->getName() );
        }

        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue( $value )
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if( $this->label === null )
        {
            return $this->name;
        }

        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel( $label )
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->getFieldSet()->getModel();
    }

    /**
     * @return FieldSet
     */
    public function getFieldSet()
    {
        return $this->fieldSet;
    }

    /**
     * @param FieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet( FieldSet $fieldSet )
    {
        $this->fieldSet = $fieldSet;

        return $this;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        $value = $request->has( $this->getNameSpacedName() )
            ? $request->input( $this->getNameSpacedName() )
            : null;

        $this->getModel()->setAttribute( $this->getName(), $value );
    }

    /**
     * @param string $rules
     * @return FieldInterface
     */
    public function rules( string $rules ): FieldInterface
    {
        $this->rules = array_merge( $this->rules, explode( '|', $rules ) );

        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [ $this->getNameSpacedName() => implode( '|', $this->rules ) ];
    }

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {

    }

    /**
     * @return View
     */
    public function render()
    {
         $renderer = app()->makeWith($this->getRenderer(), [
             'field' => $this
         ]);

         return $renderer;
    }

    /**
     * @return bool
     */
    public function getDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     *
     * @return AbstractField
     */
    public function setDisabled( bool $disabled = false ): FieldInterface
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * @param bool $readOnly
     *
     * @return FieldInterface
     */
    public function setReadOnly( bool $readOnly = false ): FieldInterface
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return FieldInterface
     */
    public function setRequired( bool $required = false ): FieldInterface
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRenderer(): ?string
    {
        return $this->renderer;
    }

    /**
     * @param string|null $renderer
     *
     * @return FieldInterface
     */
    public function setRenderer( ?string $renderer = null ): FieldInterface
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return Content
     */
    public function getInfoBlock()
    {
        return $this->infoBlock;
    }

    /**
     * @param string|null $content
     *
     * @return FieldInterface
     */
    public function setInfoBlock( $content = null ): FieldInterface
    {
        $this->infoBlock = $content;

        return $this;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     *
     * @return FieldInterface
     */
    public function setRows( int $rows ): FieldInterface
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string $style
     *
     * @return FieldInterface
     */
    public function setStyle( string $style ): FieldInterface
    {
        $this->style = $style;

        return $this;
    }
}
