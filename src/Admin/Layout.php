<?php

namespace CubeSystems\Leaf\Admin;

use Closure;
use CubeSystems\Leaf\Admin\Layout\Row;
use CubeSystems\Leaf\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/**
 * Class Layout
 * @package CubeSystems\Leaf\Admin
 */
class Layout implements Renderable
{
    /**
     * @var Collection|Row[]
     */
    protected $rows;

    protected $bodyClass;

    /**
     * Layout constructor.
     * @param Closure|null $callback
     */
    public function __construct( Closure $callback = null )
    {
        $this->rows = new Collection();

        if( $callback instanceof Closure )
        {
            $callback( $this );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param mixed $content
     * @return Layout
     */
    public function body( $content )
    {
        return $this->row( $content );
    }

    /**
     * @param $content
     * @return $this
     */
    public function row( $content )
    {
        $this->rows->push( $this->createRow( $content ) );

        return $this;
    }

    /**
     * @param $content
     * @return Row
     */
    protected function createRow( $content )
    {
        if( $content instanceof Closure )
        {
            $row = new Row();
            $content( $row );

            return $row;
        }

        return new Row( $content );
    }

    /**
     * @param $class
     * @return Layout
     */
    public function bodyClass( $class )
    {
        $this->bodyClass = $class;

        return $this;
    }

    /**
     * @return string
     */
    public function build()
    {
        $contents = new Content();

        foreach( $this->rows as $row )
        {
            $contents->push( $row->render() );
        }

        return $contents;
    }

    /**
     * @return string
     */
    public function render()
    {
        $variables = [
            'content' => $this->build(),
            'bodyClass' => $this->bodyClass,
        ];

        return view( 'leaf::controllers.resource.layout', $variables )->render();
    }

}
