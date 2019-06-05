<?php

namespace Arbory\Base\Html;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Elements\Inputs\Option;
use Arbory\Base\Html\Elements\Inputs\Select;
use Arbory\Base\Html\Elements\Inputs\CheckBox;
use Arbory\Base\Html\Elements\Inputs\Textarea;

/**
 * Class Html.
 *
 * @method static Element   title($content = null)
 * @method static Element   meta()
 *
 * @method static Element   header($content = null)
 * @method static Element   footer($content = null)
 * @method static Element   section($content = null)
 * @method static Element   fieldset($content = null)
 * @method static Element   menu($content = null)
 * @method static Element   nav($content = null)
 *
 * @method static Element   h1($content = null)
 * @method static Element   h2($content = null)
 * @method static Element   h3($content = null)
 * @method static Element   h4($content = null)
 * @method static Element   h5($content = null)
 * @method static Element   h6($content = null)
 *
 * @method static Element   div($content = null)
 * @method static Element   span($content = null)
 * @method static Element   strong($content = null)
 * @method static Element   ol($content = null)
 * @method static Element   ul($content = null)
 * @method static Element   li($content = null)
 * @method static Element   i($content = null)
 * @method static Element   abbr($content = null)
 * @method static Element   hr($content = null)
 *
 * @method static Element   table($content = null)
 * @method static Element   thead($content = null)
 * @method static Element   tbody($content = null)
 * @method static Element   tfoot($content = null)
 * @method static Element   tr($content = null)
 * @method static Element   th($content = null)
 * @method static Element   td($content = null)
 *
 * @method static Element   form($content = null)
 * @method static Element   button($content = null)
 * @method static Element   label($content = null)
 *
 * @method static Element   svg($content = null)
 */
class Html
{
    /**
     * @param null $content
     * @return Input
     */
    public static function input($content = null)
    {
        return new Input($content);
    }

    /**
     * @param null $content
     * @return CheckBox
     */
    public static function checkbox($content = null)
    {
        return new CheckBox($content);
    }

    /**
     * @param null $content
     * @return Select
     */
    public static function select($content = null)
    {
        return new Select($content);
    }

    /**
     * @param null $content
     * @return Option
     */
    public static function option($content = null)
    {
        return new Option($content);
    }

    /**
     * @param null $content
     * @return Textarea
     */
    public static function textarea($content = null)
    {
        return new Textarea($content);
    }

    /**
     * @param null $content
     * @return Element
     */
    public static function image($content = null)
    {
        return new Element('img', $content);
    }

    /**
     * @param null $content
     * @return Element
     */
    public static function link($content = null)
    {
        return new Element('a', $content);
    }

    /**
     * @param $name
     * @param array $arguments
     *
     * @return Element
     */
    public static function __callStatic($name, $arguments)
    {
        $content = array_first($arguments);

        return new Element($name, $content);
    }
}
