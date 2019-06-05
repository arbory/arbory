<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\Controls\CheckboxControl;
use Arbory\Base\Admin\Form\Fields\Renderer\CheckBoxFieldRenderer;

/**
 * Class DateTime.
 */
class Deactivator extends Checkbox
{
    protected $activateAtName = 'activate_at';
    protected $expireAtName = 'expire_at';

    protected $control = CheckboxControl::class;
    protected $rendererClass = CheckBoxFieldRenderer::class;

    protected $style = 'basic';

    /**
     * @param string $name
     */
    public function __construct($name = 'deactivate')
    {
        parent::__construct($name);
    }

    public function setActivateAtName($name)
    {
        $this->activateAtName = $name;
    }

    public function setExpireAtName($name)
    {
        $this->expireAtName = $name;
    }

    public function getActivateAtName()
    {
        return $this->activateAtName;
    }

    public function getExpireAtName()
    {
        return $this->expireAtName;
    }

    public function beforeModelSave(Request $request)
    {
        $value = $request->has($this->getNameSpacedName())
            ? $request->input($this->getNameSpacedName())
            : null;

        if ($value) {
            $this->getModel()->setAttribute($this->getActivateAtName(), null);
            $this->getModel()->setAttribute($this->getExpireAtName(), null);
        }
    }
}
