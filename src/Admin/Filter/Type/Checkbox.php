<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

/**
 * Class Checkbox
 * @package Arbory\Base\Admin\Filter\Type
 */
class Checkbox extends Type
{
    /**
     * @var string
     */
    protected $action = '=';

    /**
     * Checkbox constructor.
     * @param null $content
     * @param null $column
     */
    function __construct($content = null, $column = null)
    {
        $this->content = $content;
        $this->column = $column;
        $this->request = request();
    }

    /**
     * @return null
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return Content
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function render()
    {
        return new Content([
            Html::div([
                Html::label([
                    Html::input($this->content)
                        ->setType('checkbox')
                        ->addAttributes(['value' => 1])
                        ->addAttributes([$this->getCheckboxStatus()])
                        ->setName($this->column->getName()),
                ]),
            ])->addClass('checkbox'),
        ]);
    }
}
