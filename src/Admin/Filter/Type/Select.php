<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Admin\Filter\Type;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

/**
 * Class Select
 * @package Arbory\Base\Admin\Filter\Type
 */
class Select extends Type
{
    /**
     * @var array
     */
    protected $action = '=';

    /**
     * Select constructor.
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
     * @return array
     */
    protected function getOptionList()
    {
        $defaultOption = Html::option();

        if ($this->request->has($this->column->getName())) {
            $defaultOption->addAttributes(['selected']);
        }

        $options[] = $defaultOption;

        foreach ($this->content as $key => $value) {
            $options[] = Html::option([$value])
                ->addAttributes(['value' => $key]);
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
                ])->setName($this->column->getName()),
            ])->addClass('select'),
        ]);
    }
}
