<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;

/**
 * Class Password.
 */
class Password extends ControlField
{
    protected $classes = [
        'text',
    ];

    protected $attributes = [
        'type' => 'password',
    ];

    public function getValue()
    {
    }

    /**
     * @param Request $request
     * @return void
     */
    public function beforeModelSave(Request $request)
    {
        $password = $request->input($this->getNameSpacedName());
        $hasher = \Sentinel::getUserRepository()->getHasher();

        if ($password) {
            $this->getModel()->setAttribute($this->getName(), $hasher->hash($password));
        }
    }
}
