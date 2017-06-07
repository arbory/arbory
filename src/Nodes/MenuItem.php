<?php

namespace CubeSystems\Leaf\Nodes;

use CubeSystems\Leaf\Admin\Module\OLDRoute;
use CubeSystems\Leaf\Auth\Roles\Role;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuItem
 * @package CubeSystems\Leaf\Nodes
 */
class MenuItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'admin_menu_items';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'parent_id',
        'module',
        'after_id'
    ];

    /**
     * @var string
     */
    protected static $rolesModel = Role::class;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            static::$rolesModel, 'admin_role_menu_items', 'menu_item_id', 'role_id'
        )->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(
            self::class, 'parent_id', 'id'
        );
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        if( !$this->getModule() )
        {
            $items = self::query()->where( 'parent_id', $this->getId() )->get();

            $items->transform( function( MenuItem $item ) {
                return $item->getConfiguration();
            });

            return [
                'title' => $this->getTitle(),
                'items' => $items->toArray()
            ];
        }

        return [
            'title' => $this->getTitle(),
            'module_name' => $this->getModule(),
            'route_name' => sprintf( 'admin.%s.index', OLDRoute::generateSlugFromClassName( $this->getModule() ) )
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return (int) $this->parent_id;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return (bool) $this->getParentId();
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return int
     */
    public function getAfterId()
    {
        return $this->after_id;
    }

    /**
     * @return bool
     */
    public function isAfter(): bool
    {
        return (bool) $this->getAfterId();
    }

    /**
     * @return bool
     */
    public function hasModule(): bool
    {
        return (bool) $this->module;
    }
}
