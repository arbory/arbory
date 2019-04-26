<?php

namespace Arbory\Base\Admin\Filter;

use Illuminate\Http\Request;

/**
 * Class Type
 * @package Arbory\Base\Admin\Filter
 */
class Type
{
    const CHECKED = 'checked';

    const SELECTED = 'selected';

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
     * @var bool
     */
    protected $openByDefault;

    /**
     * Type constructor.
     * @param mixed|null $content
     * @param string|null $column
     * @param bool $accordionOpen
     */
    public function __construct($content = null, string $column = null, bool $accordionOpen = false)
    {
        $this->content = $content;
        $this->column = $column;
        $this->request = request();
        $this->openByDefault = $accordionOpen;
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

    /**
     * @return bool
     */
    public function isOpenByDefault(): bool
    {
        return $this->openByDefault;
    }

    /**
     * @return string
     */
    public function getColumnFromArrayString(): string
    {
        $columnName = $this->column;

        if (strpos($columnName, '.')) {
            $dotPosition = strpos($columnName, '.');
            $columnName = substr($columnName, 0, $dotPosition);
        }

        return $columnName;
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
    public function getAccordionBodyClass():? string
    {
        return $this->isOpenByDefault() ? 'body' : 'body closed';
    }

    /**
     * @return string|null
     */
    public function getAccordionLabelIcon():? string
    {
        return $this->isOpenByDefault() ? 'minus' : 'plus';
    }

    /**
     * @return string|null
     */
    public function getCheckboxStatus():? string
    {
        $status = null;

        if ($this->request->has($this->column)) {
            $status = self::CHECKED;
        }

        return $status;
    }

    /**
     * @param string $range
     * @return string|null
     */
    public function getRangeValue(string $range):? string
    {
        $value = $this->request->get($this->column);

        if (!$value) {
            return null;
        }

        return $value[$range] ?? null;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getCheckboxStatusFromArray(string $key):? string
    {
        $checkboxes = $this->request->get($this->getColumnFromArrayString(), []);

        return in_array($key, $checkboxes) ? self::CHECKED : null;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getSelectStatus(string $key):? string
    {
        $columnKey = $this->request->get($this->getColumnFromArrayString());

        return $columnKey === $key
            ? self::SELECTED
            : null;
    }
}