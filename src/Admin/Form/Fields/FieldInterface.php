<?php
namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Interface FieldInterface
 * @package Arbory\Base\Admin\Form\Fields
 */
interface FieldInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param $name string
     * @return $this
     */
    public function setName( $name );

    /**
     * @return string
     */
    public function getNameSpacedName();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param $value string
     * @return $this
     */
    public function setValue( $value );

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel( $label );

    /**
     * @return FieldSet
     */
    public function getFieldSet();

    /**
     * @param FieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet( FieldSet $fieldSet );

    /**
     * @return Model
     */
    public function getModel();

    /**
     * @param string $rules
     * @return FieldInterface
     */
    public function rules( string $rules ): FieldInterface;

    /**
     * @return array
     */
    public function getRules(): array;

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request );

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request );

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Element|string
     */
    public function render();

    /**
     * @return bool
     */
    public function getRequired(): bool;

    /**
     * @param bool $required
     *
     * @return mixed
     */
    public function setRequired( bool $required = false ): FieldInterface;

    /**
     * @return bool
     */
    public function getDisabled(): bool;

    /**
     * @param bool $disabled
     *
     * @return FieldInterface
     */
    public function setDisabled( bool $disabled = false ): FieldInterface;

    /**
     * @return bool
     */
    public function getReadOnly(): bool;

    /**
     * @param bool $readOnly
     *
     * @return FieldInterface
     */
    public function setReadOnly( bool $readOnly = false ): FieldInterface;

    /**
     * @return string|null
     */
    public function getRenderer(): ?string;

    /**
     * @param string|null $renderable
     *
     * @return FieldInterface
     */
    public function setRenderer( ?string $renderable = null ): FieldInterface;

    /**
     * @return string|null
     */
    public function getInfoBlock();

    /**
     * @param string|null $content
     *
     * @return FieldInterface
     */
    public function setInfoBlock( $content = null ): FieldInterface;

    /**
     * @param int $rows
     *
     * @return FieldInterface
     */
    public function setRows( int $rows ): FieldInterface;

    /**
     * @return int
     */
    public function getRows(): int;
}
