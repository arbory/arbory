<?php
namespace Arbory\Base\Admin\Panels;

use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Widgets\Button;

class Panel implements PanelInterface
{
    /**
     * @var mixed
     */
    protected $contents;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Button[]
     */
    protected $buttons = [];

    /**
     * @var Toolbox
     */
    protected $toolbox;

    public function __construct()
    {
        $this->toolbox = new Toolbox(null);
    }


    /**
     * @param Toolbox $toolbox
     *
     * @return Toolbox
     */
    public function toolbox( Toolbox $toolbox ): Toolbox
    {
        return $this->toolbox;
    }

    /**
     * @return Button[]
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     *
     * @return Panel
     */
    public function setContents( $contents )
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @param mixed $title
     *
     * @return Panel
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param mixed $buttons
     *
     * @return Panel
     */
    public function setButtons( $buttons ): self
    {
        $this->buttons = $buttons;

        return $this;
    }

    /**
     * @param $name
     * @param $url
     *
     * @return Panel
     */
    public function addToolbox( $name, $url )
    {
        if(! $this->toolbox->getMenu()) {
            $this->toolbox->setMenu(new ToolboxMenu(null));
        }

        $this->toolbox->getMenu()->add($name, $url);

        return $this;
    }

    /**
     * @param Button $button
     *
     * @return $this
     */
    public function addButton(Button $button)
    {
        $this->buttons[] = $button;

        return $this;
    }

    /**
     * Build the panel
     */
    public function build()
    {

    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        $this->build();

        return $this->getContents();
    }

    /**
     * @return mixed
     */
    public function getToolbox(): ?Toolbox
    {
        if($this->toolbox === null) {
            $this->toolbox = new Toolbox();
        }

        return $this->toolbox;
    }
}