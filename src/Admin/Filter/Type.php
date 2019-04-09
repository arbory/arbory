<?php

namespace Arbory\Base\Admin\Filter;

use Illuminate\Http\Request;

/**
 * Class Type
 * @package Arbory\Base\Admin\Filter
 */
class Type
{
    /**
     * @var string|array
     */
    protected $action;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var string
     */
    protected $column;

    /**
     * Type constructor.
     * @param mixed|null $content
     * @param string|null $column
     */
    public function __construct($content = null, string $column = null )
    {
        $this->content = $content;
        $this->column = $column;
        $this->request = request();
    }

    /**
     * @return mixed
     */
    public function getAction() : array
    {
        return array_wrap($this->action);
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    public function getColumnFromArrayString() : string
    {
        return substr($this->column, 0, strpos($this->column, '.'));
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * @return string|null
     */
    public function getCheckboxStatus()
    {
        if ($this->request->has($this->column)) {
            return 'checked';
        }

        return null;
    }

    /**
     * @param $valueSide
     * @return string|null
     */
    public function getRangeValue($valueSide)
    {
        if (!$this->request->has($this->column)) {
            return null;
        }

        $value = $this->request->get($this->column);

        if ($value[$valueSide]) {
            return 'value="' . $value[$valueSide] . '"';
        }

        return null;
    }

    /**
     * @param $key
     * @return string|null
     */
    public function getCheckboxStatusFromArray($key)
    {
        if ($this->request->has($this->getColumnFromArrayString())) {
            $array = $this->request->get($this->getColumnFromArrayString());

            foreach ($array as $item) {
                if ($item == $key) {
                    return 'checked';
                }
            }
        }

        return null;
    }

    /**
     * @param $key
     * @return string|null
     */
    public function getSelectStatus($key)
    {
        if ($this->request->has($this->getColumnFromArrayString())) {
            if ($this->request->get($this->getColumnFromArrayString()) == $key) {
                return 'selected';
            }
        }

        return null;
    }
}