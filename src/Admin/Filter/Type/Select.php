<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

/**
 * Class Select
 * @property mixed selected
 * @package Arbory\Base\Admin\Filter\Type
 */
class Select extends Type
{
    /**
     * @var array
     */
    protected $action = '=';

    public function __construct($content = null, ?string $column = null)
    {
        parent::__construct($content, $column);
        $this->selected = $this->getSelectedValue();
    }

    /**
     * @return array
     */
    protected function getOptionList()
    {
        $options[] = Html::option();

        if (!is_null($this->content)) {
            foreach ($this->content as $key => $value) {
                $options[] = Html::option([$value])
                    ->addAttributes(['value' => $key])
                    ->addAttributes([$this->getSelectStatus($key)]);
            }
        }

        return $options;
    }

    /**
     * @return Content
     */
    public function render()
    {
        return new Content([
            Html::div([
                Html::select([
                    $this->getOptionList(),
                ])->setName($this->getColumnFromArrayString()),
            ])->addClass('select'),
        ]);
    }

    public function getSelectedValue()
    {
        return $this->request->get($this->getColumnFromArrayString());
    }
}
