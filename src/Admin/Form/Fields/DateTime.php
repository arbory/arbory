<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class DateTime
 * @package Arbory\Base\Admin\Form\Fields
 */
class DateTime extends Text
{
    protected $carbonFormat = 'Y-m-d H:i:s';

    public $allowNull = false;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * @return Element
     */
    public function render()
    {
        return (new Renderer\DateFieldRenderer($this))->render();
    }

    /**
     * @param bool $allowNull
     * @return Date
     */
    public function allowNull($allowNull = true)
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNullAllowed()
    {
        return $this->allowNull;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave(Request $request)
    {
        $value = $request->has($this->getNameSpacedName())
            ? $request->input($this->getNameSpacedName())
            : null;

        $this->getModel()->setAttribute($this->getName(), $this->prepareForCarbon($value));
    }

    /**
     * @param $value
     * @return string|null
     */
    protected function prepareForCarbon($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i', $value)->format($this->carbonFormat) : null;
    }
}
