<?php declare( strict_types=1 );

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Inputs\Input;
use CubeSystems\Leaf\Html\Html;

/**
 * Class ImageFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
final class ImageFieldRenderer extends FileFieldRenderer
{
    /**
     * @return \CubeSystems\Leaf\Html\Elements\Element
     */
    public function render()
    {
        $input = $this->getInput();
        $label = $input->getLabel( $this->field->getLabel() );
        $image = $this->field->getValue();

        $value = Html::div();

        $leafFile = $this->getFile();

        if( $leafFile )
        {
            $value->append( Html::image()->addAttributes( [ 'src' => $image->getUrl() ] ) );
            $value->append( $this->createFileDetails( $leafFile ) );
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
