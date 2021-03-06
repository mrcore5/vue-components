<?php namespace Mrcore\Components\Providers;

use Gate;
use View;
use Event;
use Layout;
use Module;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ComponentsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Mrcore Module Tracking
        Module::trace(get_class(), __function__);

        // Register facades and class aliases
        $this->registerFacades();

        // Register configs
        $this->registerConfigs();

        // Register services
        $this->registerServices();

        // Register testing environment
        $this->registerTestingEnvironment();

        // Register mrcore modules
        $this->registerModules();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Kernel $kernel, Router $router)
    {
        // Mrcore Module Tracking
        Module::trace(get_class(), __function__);

        // Register publishers
        $this->registerPublishers();

        // Register migrations
        $this->registerMigrations();

        // Register Policies
        $this->registerPolicies();

        // Register global and route based middleware
        $this->registerMiddleware($kernel, $router);

        // Register event listeners and subscriptions
        $this->registerListeners();

        // Register scheduled tasks
        $this->registerSchedules();

        // Register mrcore layout overrides
        $this->registerLayout();

        // Register artisan commands
        $this->registerCommands();
    }



    /**
     * Register facades and class aliases.
     *
     * @return void
     */
    protected function registerFacades()
    {
        #$facade = AliasLoader::getInstance();
        #$facade->alias('Components', \Mrcore\Components\Facades\Components::class);
        #class_alias('Some\Long\Class', 'Short');
    }

    /**
     * Register additional configs and merges.
     *
     * @return void
     */
    protected function registerConfigs()
    {
        // Append or overwrite configs
        #config(['test' => 'hi']);

        // Merge configs
        #$this->mergeConfigFrom(__DIR__.'/../Config/components.php', 'mrcore.components');
    }

    /**
     * Register the application and other services.
     *
     * @return void
     */
    protected function registerServices()
    {
        // Register IoC bind aliases and singletons
        #$this->app->alias(\Mrcore\Components\Components::class, \Mrcore\Components::class)
        #$this->app->singleton(\Mrcore\Components\Components::class, \Mrcore\Components::class)

        // Register other service providers
        #$this->app->register(\Mrcore\Components\Providers\OtherServiceProvider::class);
    }

    /**
     * Register test environment overrides
     *
     * @return void
     */
    public function registerTestingEnvironment()
    {
        // Does not apply if NOT running in 'testing' mode
        if (!$this->app->environment('testing')) return;
    }

    /**
     * Register mrcore modules
     *
     * @return void
     */
    public function registerModules()
    {
        // Register mrcore modules
        #Module::register('Mrcore\Other', $forceEvenIfConsoleOnly=true);
        #Module::loadViews('Mrcore\Other'); // If you need to use this modules view::
    }

    /**
     * Define the published resources and configs.
     *
     * @return void
     */
    protected function registerPublishers()
    {
        // Only applies if running in console
        if (!$this->app->runningInConsole()) return;

        /*
        // Register additional css assets with mrcore Layout
        Layout::css('css/wiki-bundle.css');

        // App base path
        $path = realpath(__DIR__.'/../');

        // Config publishing rules
        // ./artisan vendor:publish --tag="mcore.components.configs"
        $this->publishes([
            "$path/Config" => base_path('/config/mrcore'),
        ], 'mrcore.components.configs');

        // Migration publishing rules
        // ./artisan vendor:publish --tag="mrcore.components.migrations"
        $this->publishes([
            "$path/Database/Migrations" => base_path('/database/migrations'),
        ], 'mrcore.components.migrations');

        // Seed publishing rules
        // ./artisan vendor:publish --tag="mrcore.components.seeds"
        $this->publishes([
            "$path/Database/Seeds" => base_path('/database/seeds'),
        ], 'mrcore.components.seeds');
        */
    }

    /**
     * Register the migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        // Only applies if running in console
        if (!$this->app->runningInConsole()) return;

        // Register Migrations
        #$this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    /**
     * Register permission policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        // Define permissions (closure or Class@method)
        #Gate::define('update-post', function($user, $post) {
        #    return $user->id === $post->user_id;
        #});

        #Gate::before(function ($user, $ability) {
        #    if ($user->isSuperAdmin()) {
        #        return true;
        #    }
        #});
        # ->after() is also available

        // Or define an entire policy class
        #Gate::policy(\App\Post::class, \App\Policies\PostPolicy::class);
    }

    /**
     * Register global and route based middleware.
     *
     * @param Illuminate\Contracts\Http\Kernel $kernel
     * @param \Illuminate\Routing\Router $router
     * @return  void
     */
    protected function registerMiddleware(Kernel $kernel, Router $router)
    {
        // Register global middleware
        #$kernel->pushMiddleware('Mrcore\Components\Http\Middleware\DoSomething');

        // Register route based middleware
        // FIXME Laravel version 5.3 vs 5.5 hack, remove when 5.3 is deprecated at dynatron
        #$version = app()->version();
        #if (substr($version, 0, 3) == '5.3') {
            #$router->middleware('auth.admin', \Mrcore\Components\Http\Middleware\AuthenticateAdmin::class);
        #} else {
            #$router->aliasMiddleware('auth.admin', \Mrcore\Components\Http\Middleware\AuthenticateAdmin::class);
        #}
    }

    /**
     * Register event listeners and subscriptions.
     *
     * @return void
     */
    protected function registerListeners()
    {
        // Login event listener
        #Event::listen('Illuminate\Auth\Events\Login', function($user) {
            //
        #});

        // Logout event subscriber
        #Event::subscribe('Mrcore\Components\Listeners\MyEventSubscription');
    }

    /**
     * Register the scheduled tasks
     *
     * @return void
     */
    protected function registerSchedules()
    {
        // Only applies if running in console
        if (!$this->app->runningInConsole()) return;

        // Register all task schedules for this hostname ONLY if running from the schedule:run command
        /*if (app()->runningInConsole() && isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'schedule:run') {

            // Defer until after all providers booted, or the scheduler instance is removed from Illuminate\Foundation\Console\Kernel defineConsoleSchedule()
            $this->app->booted(function() {

                // Get the scheduler instance
                $schedule = app('Illuminate\Console\Scheduling\Schedule');

                // Define our schedules
                $schedule->call(function() {
                    echo "hi";
                })->everyMinute();

            });
        }*/
    }

    /**
     * Register mrcore layout overrides.
     *
     * @return void
     */
    protected function registerLayout()
    {
        // Does not apply if running in console
        if ($this->app->runningInConsole()) return;

        // Register additional css assets with mrcore Layout
        #Layout::css('css/wiki-bundle.css');

        // Share data wiht all views
        #View::share('key', 'value');
    }

        /**
     * Register artisan commands.
     * @return void
     */
    protected function registerCommands()
    {
        // Only applies if running in console
        if (!$this->app->runningInConsole()) return;

        // Register Commands
        #$this->commands([
        #    \Mrcore\Components\Console\Commands\AppCommand::class
        #]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // Only required if $defer = true and you add bindings in register()
        // Only use if the provier is super simple and basically only has a simle binding
        //return ['Mrcore\Appstub\Stuff', 'other bindings...'];
    }
}
