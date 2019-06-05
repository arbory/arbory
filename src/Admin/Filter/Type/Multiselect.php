<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;

/**
 * Class Multiselect.
 */
class Multiselect extends Type
{
    /**
     * @var string
     */
    protected $action = '=';

    /**
     * @return array|null
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    protected function getCheckboxList():? array
    {
        $content = $this->content;
        if (! is_null($content)) {
            foreach ($content as $key => $value) {
                $options[] = Html::label([
                    Html::input($value)
                        ->setType('checkbox')
                        ->addAttributes(['value' => $key, $this->getCheckboxStatusFromArray($key)])
                        ->setName($this->getColumnFromArrayString().'[]'),
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
