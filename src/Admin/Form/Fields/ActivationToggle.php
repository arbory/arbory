<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form\Fields\Renderer\CheckBoxFieldRenderer;

/**
 * Class DateTime
 * @package Arbory\Base\Admin\Form\Fields
 */
class ActivationToggle extends AbstractField
{
    protected $activateAtName = 'activate_at';

    protected $expireAtName = 'expire_at';

    /**
     * @param string $name
     */
    public function __construct($name)
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
        $this->getModel()->setAttribute($this->getActivateAtName(), null);
        $this->getModel()->setAttribute($this->getExpireAtName(), null);
    }

    /**
     * @return Element
     */
    public function render()
    {
        return (new CheckBoxFieldRenderer($this))->render();
    }
}
