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
     * Filter constructor.
     * @param null $content
     */
    function __construct( $content ) {
        $this->content = $content;
    }

    /**
     * @return array
     */
    protected function getOptionList() {
        $options[] = Html::option()->addAttributes( [ 'selected' ] );

        foreach ( $this->content as $key => $value ) {
            $options[] = Html::option( [ $value ] )->addAttributes( [ 'value' => $key ] );
        }

        return $options;
    }

    /**
     * @return Content
     */
    public function render()
    {
        return new Content([Html::div( [
            Html::select( [
                $this->getOptionList(),
            ] ),
        ] )->addClass( 'select' )]);
    }
}
