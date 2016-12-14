<?php

use Cartalyst\Sentinel\Sentinel;
use Illuminate\Database\Seeder;

class LeafDatabaseSeeder extends Seeder
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * LeafDatabaseSeeder constructor.
     * @param Sentinel $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->sentinel = $sentinel;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Users
        DB::table( 'users' )->truncate();

        $admin = $this->sentinel->getUserRepository()->create( [
            'email' => 'admin@cubesystems.lv',
            'password' => 'password'
        ] );

        $user = $this->sentinel->getUserRepository()->create( [
            'email' => 'user@cubesystems.lv',
            'password' => 'password'
        ] );

        // Create Activations
        DB::table( 'activations' )->truncate();

        $code = Activation::create( $admin )->code;
        Activation::complete( $admin, $code );

        $code = Activation::create( $user )->code;
        Activation::complete( $user, $code );

        // Create Roles
        $administratorRole = $this->sentinel->getRoleRepository()->create( [
            'name' => 'Administrator',
            'slug' => 'administrator',
            'permissions' => [
                'users.create' => true,
                'users.update' => true,
                'users.view' => true,
                'users.destroy' => true,
                'roles.create' => true,
                'roles.update' => true,
                'roles.view' => true,
                'roles.delete' => true
            ]
        ] );
        $moderatorRole = $this->sentinel->getRoleRepository()->create( [
            'name' => 'Moderator',
            'slug' => 'moderator',
            'permissions' => [
                'users.update' => true,
                'users.view' => true,
            ]
        ] );
        $subscriberRole = $this->sentinel->getRoleRepository()->create( array(
            'name' => 'Subscriber',
            'slug' => 'subscriber',
            'permissions' => []
        ) );

        // Assign Roles to Users
        $administratorRole->users()->attach( $admin );
        $subscriberRole->users()->attach( $user );
    }
}
