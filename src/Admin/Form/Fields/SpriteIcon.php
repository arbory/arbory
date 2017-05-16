<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SpriteIcon extends Select
{
    /**
     * @var string
     */
    protected $spritePath;

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
     * @return Collection
     * @throws \InvalidArgumentException
     */
    public function getOptions(): Collection
    {
        return $this->getIconIds();


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
            throw new \InvalidArgumentException( 'Provided sprite-sheet doesn\'t exist' );
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
                    $id = (string)$attributeValue;
                }
            }

            if( $id )
            {
                $ids->push( $id );
            }
        }

        return $ids;
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