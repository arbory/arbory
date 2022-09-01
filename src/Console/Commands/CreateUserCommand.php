<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Services\Permissions\ModulePermissionsRegistry;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Auth\Users\User;
use Cartalyst\Sentinel\Sentinel;
use Cartalyst\Sentinel\Roles\RoleInterface;
use Cartalyst\Sentinel\Users\UserInterface;
use Arbory\Base\Http\Controllers\Admin\DashboardController;

/**
 * Class CreateUserCommand.
 */
class CreateUserCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'arbory:create-user';

    /**
     * @var string
     */
    protected $description = 'Create a new Arbory admin user';

    /**
     * @param  Sentinel  $sentinel
     */
    public function __construct(protected Sentinel $sentinel)
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle()
    {
        $this->createAdminUser();
    }

    /**
     * @return UserInterface|User
     */
    protected function createAdminUser(): UserInterface|User
    {
        $this->info('Let\'s create admin user');

        $users = $this->sentinel->getUserRepository();
        $user = null;

        while ($user === null) {
            $login = $this->ask('Admin email');
            $password = $this->secret('What is the password?');

            if ($this->loginExists($login)) {
                $this->error('User with login "'.$login.'" already exists');
                continue;
            }

            try {
                $user = $users->create([
                    'email' => $login,
                    'password' => $password,
                ]);

                $this->activateUser($user);
            } catch (InvalidArgumentException $exception) {
                $this->error($exception->getMessage());
            }
        }

        $role = $this->getUserRole();
        $role->users()->attach($user);

        $this->info('User '.$user->getUserLogin().' created.');

        return $user;
    }

    /**
     * @param $login
     */
    protected function loginExists($login): bool
    {
        $users = $this->sentinel->getUserRepository();

        return $users->where([
            $users->getUserLoginName() => $login,
        ])->exists();
    }

    protected function activateUser(UserInterface $user): bool
    {
        $activations = $this->sentinel->getActivationRepository();
        $activation = $activations->create($user);

        return $activations->complete($user, $activation->getCode());
    }

    protected function getUserRole(): RoleInterface
    {
        $repository = $this->sentinel->getRoleRepository();
        $roles = $repository->all();

        if (! $roles->count()) {
            return $this->createNewRole();
        }

        if ($roles->count() === 1) {
            return $roles->first();
        }

        return $this->chooseFromExistingRoles();
    }

    private function createNewRole(): RoleInterface
    {
        $repository = $this->sentinel->getRoleRepository();

        return $repository->create([
            'name' => 'Administrator',
            'slug' => 'administrator',
            'permissions' => $this->getPermissions(),
        ]);
    }

    private function getPermissions(): array
    {
        $permissions = [];
        $modules = Arr::flatten(array_merge([DashboardController::class], config('arbory.menu')));
        foreach ($modules as $module) {
            foreach (ModulePermissionsRegistry::DEFAULT_PERMISSIONS as $permission) {
                $permissions[$module . '.' . $permission] = true;
            }
        }

        return $permissions;
    }

    private function chooseFromExistingRoles(): RoleInterface
    {
        $repository = $this->sentinel->getRoleRepository();
        $roles = $repository->all();

        $roleOptions = $roles->map(fn(RoleInterface $role) => $role->getName())->toArray();

        $role = null;

        while ($role === null) {
            $name = $this->choice('Choose user role', $roleOptions);

            $role = $roles->first(fn(RoleInterface $role) => $role->getName() === $name);
        }

        return $role;
    }
}
