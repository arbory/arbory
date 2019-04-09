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
     * @return array
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    protected function getCheckboxList()
    {
        if (!is_null($this->content)) {
            foreach ($this->content as $key => $value) {
                $options[] = Html::label([
                    Html::input($value)
                        ->setType('checkbox')
                        ->addAttributes(['value' => $key])
                        ->addAttributes([$this->getCheckboxStatusFromArray($key)])
                        ->setName($this->getColumnFromArrayString() . '[]'),
                ]);
            }

            return $options;
        }

        return null;
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
