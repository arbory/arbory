<?php

namespace CubeSystems\Leaf\Nodes;

use CubeSystems\Leaf\Admin\Module\Route;
use CubeSystems\Leaf\Auth\Roles\Role;
use Illuminate\Database\Eloquent\Model;

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
        'parent',
        'module',
        'after'
    ];

    /**
     * @var string
     */
    protected static $rolesModel = Role::class;

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany(
            static::$rolesModel, 'admin_role_menu_items', 'menu_item_id', 'role_id'
        )->withTimestamps();
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        if ( !$this->getModule() )
        {
            $items = self::query()->where( 'parent', $this->getId() )->get();

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
            'route_name' => sprintf( 'admin.%s.index', Route::generateSlugFromClassName( $this->getModule() ) )
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
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return (bool) $this->getParent();
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
    public function getAfter()
    {
        return $this->after;
    }

    /**
     * @return bool
     */
    public function isAfter(): bool
    {
        return (bool) $this->after;
    }

    /**
     * @return bool
     */
    public function hasModule(): bool
    {
        return (bool) $this->module;
    }
}