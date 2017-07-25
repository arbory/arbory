<?php declare( strict_types=1 );

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Html;

/**
 * Class ImageFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
final class ImageFieldRenderer extends FileFieldRenderer
{
    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        $input = $this->getInput();
        $label = $input->getLabel( $this->field->getLabel() );
        $image = $this->field->getValue();

        $value = Html::div();

        $arboryFile = $this->getFile();

        if( $arboryFile )
        {
            $value->append( Html::image()->addAttributes( [ 'src' => $image->getUrl() ] ) );
            $value->append( $this->createFileDetails( $arboryFile ) );
        }

        $value->append( $input );

        return $this->buildField( $label, $value );
    }

    /**
     * @return Input
     */
    protected function getInput(): Input
    {
        return parent::getInput()->addAttributes( [
            'accept' => 'image/*'
        ] );
    }
}
