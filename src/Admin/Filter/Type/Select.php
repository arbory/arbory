<?php

namespace Arbory\Base\Admin\Filter\Type;

use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Illuminate\Http\Request;

/**
 * Class Dropdown
 * @package Arbory\Base\Admin\Form\Fields
 */
class Select
{
    /**
     * Filter constructor.
     * @param $columns
     */
    function __construct( $options ) {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @return null
     */
    protected function getOptions() {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    protected function htmlOptions() {
        $options = [];

        foreach ( $this->options as $key => $value ) {
            $options[] = Html::option( [ $value ] )->addAttributes( [ 'value' => $key ] );
        }

        return $options;
    }

    /**
     * @return Content
     */
    protected function render()
    {
        return new Content([Html::div( [
            Html::select( [
                $this->htmlOptions(),
            ] ),
        ] )->addClass( 'select' )]);
    }
}
