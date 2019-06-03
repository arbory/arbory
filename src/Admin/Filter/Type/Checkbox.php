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
                        ->setName($this->column),
                ]),
            ])->addClass('checkbox'),
        ]);
    }
}
