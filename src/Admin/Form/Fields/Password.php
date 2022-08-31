<?php

namespace Arbory\Base\Admin\Form\Fields;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

/**
 * Class Password.
 */
class Password extends ControlField
{
    protected array $classes = [
        'text',
    ];

    protected array $attributes = [
        'type' => 'password',
    ];

    public function getValue()
    {
    }

    /**
     * @return void
     */
    public function beforeModelSave(Request $request)
    {
        $password = $request->input($this->getNameSpacedName());
        $hasher = Sentinel::getUserRepository()->getHasher();

        if ($password) {
            $this->getModel()->setAttribute($this->getName(), $hasher->hash($password));
        }
    }
}
