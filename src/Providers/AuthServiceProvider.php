<?php

namespace Arbory\Base\Providers;

use Exception;
use Illuminate\Http\Response;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Auth\Users\User;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Auth\Reminders\Reminder;
use Arbory\Base\Auth\Throttling\Throttle;
use Arbory\Base\Auth\Activations\Activation;
use Cartalyst\Sentinel\Hashing\NativeHasher;
use Arbory\Base\Auth\Persistences\Persistence;
use Cartalyst\Sentinel\Cookies\IlluminateCookie;
use Cartalyst\Sentinel\Sessions\IlluminateSession;
use Cartalyst\Sentinel\Checkpoints\ThrottleCheckpoint;
use Cartalyst\Sentinel\Roles\IlluminateRoleRepository;
use Cartalyst\Sentinel\Users\IlluminateUserRepository;
use Cartalyst\Sentinel\Checkpoints\ActivationCheckpoint;
use Cartalyst\Sentinel\Reminders\IlluminateReminderRepository;
use Cartalyst\Sentinel\Throttling\IlluminateThrottleRepository;
use Cartalyst\Sentinel\Activations\IlluminateActivationRepository;
use Cartalyst\Sentinel\Persistences\IlluminatePersistenceRepository;

/**
 * Class AuthServiceProvider.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->garbageCollect();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerPersistences();
        $this->registerUsers();
        $this->registerRoles();
        $this->registerCheckpoints();
        $this->registerReminders();
        $this->registerSentinel();
    }

    /**
     * Registers the persistences.
     *
     * @return void
     */
    protected function registerPersistences()
    {
        $this->registerSession();
        $this->registerCookie();

        $this->app->singleton('sentinel.persistence', function ($app) {
            return new IlluminatePersistenceRepository(
                $app['sentinel.session'],
                $app['sentinel.cookie'],
                Persistence::class
            );
        });
    }

    /**
     * Registers the session.
     *
     * @return void
     */
    protected function registerSession()
    {
        $this->app->singleton('sentinel.session', function ($app) {
            return new IlluminateSession(
                $app['session.store'],
                'arbory_admin'
            );
        });
    }

    /**
     * Registers the cookie.
     *
     * @return void
     */
    protected function registerCookie()
    {
        $this->app->singleton('sentinel.cookie', function ($app) {
            return new IlluminateCookie(
                $app['request'],
                $app['cookie'],
                'arbory_admin'
            );
        });
    }

    /**
     * Registers the users.
     *
     * @return void
     */
    protected function registerUsers()
    {
        $this->registerHasher();

        $this->app->singleton('sentinel.users', function ($app) {
            return new IlluminateUserRepository(
                $app['sentinel.hasher'],
                $app['events'],
                User::class
            );
        });
    }

    /**
     * Registers the hahser.
     *
     * @return void
     */
    protected function registerHasher()
    {
        $this->app->singleton('sentinel.hasher', function () {
            return new NativeHasher;
        });
    }

    /**
     * Registers the roles.
     *
     * @return void
     */
    protected function registerRoles()
    {
        $this->app->singleton('sentinel.roles', function () {
            return new IlluminateRoleRepository(Role::class);
        });
    }

    /**
     * Registers the checkpoints.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function registerCheckpoints()
    {
        $this->registerActivationCheckpoint();

        $this->registerThrottleCheckpoint();

        $this->app->singleton('sentinel.checkpoints', function ($app) {
            return [
                $app['sentinel.checkpoint.throttle'],
                $app['sentinel.checkpoint.activation'],
            ];
        });
    }

    /**
     * Registers the activation checkpoint.
     *
     * @return void
     */
    protected function registerActivationCheckpoint()
    {
        $this->registerActivations();

        $this->app->singleton('sentinel.checkpoint.activation', function ($app) {
            return new ActivationCheckpoint($app['sentinel.activations']);
        });
    }

    /**
     * Registers the activations.
     *
     * @return void
     */
    protected function registerActivations()
    {
        $this->app->singleton('sentinel.activations', function ($app) {
            return new IlluminateActivationRepository(
                Activation::class,
                $app['config']->get('arbory.auth.activations.expires', 259200)
            );
        });
    }

    /**
     * Registers the throttle checkpoint.
     *
     * @return void
     */
    protected function registerThrottleCheckpoint()
    {
        $this->registerThrottling();

        $this->app->singleton('sentinel.checkpoint.throttle', function ($app) {
            return new ThrottleCheckpoint(
                $app['sentinel.throttling'],
                $app['request']->getClientIp()
            );
        });
    }

    /**
     * Registers the throttle.
     *
     * @return void
     */
    protected function registerThrottling()
    {
        $this->app->singleton('sentinel.throttling', function ($app) {
            $config = $app['config']->get('arbory.auth.throttling');

            $globalInterval = array_get($config, 'global.interval');
            $globalThresholds = array_get($config, 'global.thresholds');

            $ipInterval = array_get($config, 'ip.interval');
            $ipThresholds = array_get($config, 'ip.thresholds');

            $userInterval = array_get($config, 'user.interval');
            $userThresholds = array_get($config, 'user.thresholds');

            return new IlluminateThrottleRepository(
                Throttle::class,
                $globalInterval,
                $globalThresholds,
                $ipInterval,
                $ipThresholds,
                $userInterval,
                $userThresholds
            );
        });
    }

    /**
     * Registers the reminders.
     *
     * @return void
     */
    protected function registerReminders()
    {
        $this->app->singleton('sentinel.reminders', function ($app) {
            return new IlluminateReminderRepository(
                $app['sentinel.users'],
                Reminder::class,
                $app['config']->get('arbory.auth.reminders.expires', 14400)
            );
        });
    }

    /**
     * Registers sentinel.
     *
     * @return void
     */
    protected function registerSentinel()
    {
        $this->app->singleton('sentinel', function ($app) {
            $sentinel = new Sentinel(
                $app['sentinel.persistence'],
                $app['sentinel.users'],
                $app['sentinel.roles'],
                $app['sentinel.activations'],
                $app['events']
            );

            if (isset($app['sentinel.checkpoints'])) {
                foreach ($app['sentinel.checkpoints'] as $key => $checkpoint) {
                    $sentinel->addCheckpoint($key, $checkpoint);
                }
            }

            $sentinel->setActivationRepository($app['sentinel.activations']);
            $sentinel->setReminderRepository($app['sentinel.reminders']);

            $sentinel->setRequestCredentials(function () use ($app) {
                $request = $app['request'];

                $login = $request->getUser();
                $password = $request->getPassword();

                if ($login === null && $password === null) {
                    return;
                }

                return compact('login', 'password');
            });

            $sentinel->creatingBasicResponse(function () {
                $headers = ['WWW-Authenticate' => 'Basic'];

                return new Response('Invalid credentials.', 401, $headers);
            });

            return $sentinel;
        });

        $this->app->alias('sentinel', 'Cartalyst\Sentinel\Sentinel');
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'sentinel.session',
            'sentinel.cookie',
            'sentinel.persistence',
            'sentinel.hasher',
            'sentinel.users',
            'sentinel.roles',
            'sentinel.activations',
            'sentinel.checkpoint.activation',
            'sentinel.throttling',
            'sentinel.checkpoint.throttle',
            'sentinel.checkpoints',
            'sentinel.reminders',
            'sentinel',
        ];
    }

    /**
     * Garbage collect activations and reminders.
     *
     * @return void
     */
    protected function garbageCollect()
    {
        $config = $this->app['config']->get('arbory.auth');

        $this->sweep(
            $this->app['sentinel.activations'],
            $config['activations']['lottery']
        );

        $this->sweep(
            $this->app['sentinel.reminders'],
            $config['reminders']['lottery']
        );
    }

    /**
     * Sweep expired codes.
     *
     * @param  mixed $repository
     * @param  array $lottery
     * @return void
     */
    protected function sweep($repository, array $lottery)
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (Exception $e) {
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param  array $lottery
     * @return bool
     */
    protected function configHitsLottery(array $lottery)
    {
        return mt_rand(1, $lottery[1]) <= $lottery[0];
    }
}
