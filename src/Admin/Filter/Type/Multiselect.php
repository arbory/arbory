<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

/**
 * Class Multiselect
 * @package Arbory\Base\Admin\Filter\Type
 */
class Multiselect extends Type
{
    /**
     * @var string
     */
    protected $action = '=';

    /**
     * Filter constructor.
     * @param null $content
     * @param null $column
     * @param null $relation
     */
    function __construct($content = null, $column = null, $relation = null)
    {
        $this->content = $content;
        $this->column = $column;
        $this->relation = $relation;
        $this->request = request();
    }

    /**
     * @return array
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    protected function getCheckboxList()
    {
        foreach ($this->content as $key => $value) {
            $options[] = Html::label([
                Html::input($value)
                    ->setType('checkbox')
                    ->addAttributes(['value' => $key])
                    ->addAttributes([$this->getCheckboxStatusFromArray($key)])
                    ->setName($this->getColumnName() . '[]'),
            ]);
        }

        return $options;
    }

    /**
     * @return Content
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function render()
    {
        return new Content([
            Html::div([
                $this->getCheckboxList(),
            ])->addClass('multiselect'),
        ]);
    }
}
