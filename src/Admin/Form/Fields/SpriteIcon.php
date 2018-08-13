<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\IconPickerRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SpriteIcon extends Select
{
    /**
     * @var string
     */
    protected $spritePath;

    /**
     * @var string
     */
    protected $filter;

    /**
     * @param string $name
     */
    public function __construct( $name )
    {
        $this->spritePath = config( 'arbory.fields.sprite_icon.path' );

        parent::__construct( $name );
    }

    /**
     * @param string $path
     * @return SpriteIcon
     */
    public function sprite( string $path ): self
    {
        $this->spritePath = $path;

        return $this;
    }

    /**
     * @return $this
     */
    public function filter( string $filter )
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return Collection
     * @throws \InvalidArgumentException
     */
    public function getOptions(): Collection
    {
        return $this->getIconIds()->mapWithKeys( function( $iconId )
        {
            return [ $iconId => $iconId ];
        } );
    }

    /**
     * @param string $iconId
     * @return null|\SimpleXMLElement
     */
    public function getIconContent( $iconId )
    {
        $xml = simplexml_load_string( file_get_contents( $this->spritePath ) );

        foreach( $xml->children()->symbol as $node )
        {
            /** @var \SimpleXMLElement $node */
            $id = null;

            foreach( $node->attributes() as $attributeName => $attributeValue )
            {
                if( $attributeName === 'id' )
                {
                    $id = (string) $attributeValue;
                }
            }

            if( $id === $iconId )
            {
                return $node;
            }
        }

        return null;
    }

    /**
     * @return Collection
     * @throws \InvalidArgumentException
     */
    protected function getIconIds(): Collection
    {
        $ids = new Collection();

        if( !file_exists( $this->spritePath ) )
        {
            throw new \InvalidArgumentException( sprintf( 'Provided sprite-sheet [%s] doesn\'t exist', $this->spritePath ) );
        }

        $xml = simplexml_load_string( file_get_contents( $this->spritePath ) );

        foreach( $xml->children()->symbol as $node )
        {
            /** @var \SimpleXMLElement $node */
            $id = null;

            foreach( $node->attributes() as $attributeName => $attributeValue )
            {
                if( $attributeName === 'id' )
                {
                    $id = (string) $attributeValue;
                }
            }

            if( $this->filter && !str_contains($id, $this->filter) )
            {
                continue;
            }

            if( $id )
            {
                $ids->push( $id );
            }
        }

        return $ids;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     * @throws \InvalidArgumentException
     */
    public function render()
    {
        return ( new IconPickerRenderer( $this, $this->getOptions() ) )->render();
    }

    /**
     * @param Request $request
     * @return void
     * @throws \InvalidArgumentException
     */
    public function beforeModelSave( Request $request )
    {
        $this->options( $this->getOptions() );

        parent::beforeModelSave( $request );
    }
}
