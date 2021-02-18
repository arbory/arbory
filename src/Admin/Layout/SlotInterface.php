<?php

namespace Arbory\Base\Admin\Layout;

interface SlotInterface
{
    /**
     * Name of the slot.
     *
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getContent();
}
