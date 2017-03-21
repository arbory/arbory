<?php

namespace CubeSystems\Leaf\Nodes;

/**
 * Class ContentTypesRepository
 * @package CubeSystems\Leaf\Nodes
 */
class ContentTypeRegister
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $contentTypes;

    /**
     * ContentTypesRepository constructor.
     */
    public function __construct()
    {
        $contentTypes = collect( config( 'leaf.content_types', [] ) );
        $contentTypeNames = $contentTypes->map( function ( $item )
        {
            return $this->makeNameFromType( $item );
        } );

        $this->contentTypes = $contentTypes->combine( $contentTypeNames );
    }

    /**
     * @param Node $parent
     * @return \Illuminate\Support\Collection|\string[]
     */
    public function getAllowedChildTypes( Node $parent )
    {
        if( method_exists( $parent->content, 'getAllowedChildTypes' ) )
        {
            return $parent->content->getAllowedChildTypes( $this->getAllContentTypes() );
        }

        return $this->getAllContentTypes();
    }

    /**
     * @return \Illuminate\Support\Collection|string[]
     */
    public function getAllContentTypes()
    {
        return $this->contentTypes;
    }

    /**
     * @param $type
     * @return bool
     */
    public function isValidContentType( $type )
    {
        return $this->contentTypes->has( $type );
    }

    /**
     * @param string $type
     * @return string
     */
    protected function makeNameFromType( $type )
    {
        return preg_replace( '/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', '$0', class_basename( $type ) );
    }

}
