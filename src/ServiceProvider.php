<?php

namespace Stylemix\Settings;

use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{

    /**
     * This provider is deferred and should be lazy loaded.
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Register IoC bindings.
     */
    public function register()
    {
        // Bind the manager as a singleton on the container.
        $this->app->singleton('Stylemix\Settings\SettingsManager', function ($app) {
            /**
             * Construct the actual manager.
             */
            return new SettingsManager($app);
        });

        // Provide a shortcut to the SettingStore for injecting into classes.
        $this->app->bind('Stylemix\Settings\SettingStore', function ($app) {
            return $app->make('Stylemix\Settings\SettingsManager')->driver();
        });

        $this->app->alias('Stylemix\Settings\SettingStore', 'setting');

        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'settings');
    }

    /**
     * Boot the package.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('settings.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations/create_settings_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_create_settings_table.php')
        ], 'migrations');
    }

    /**
     * Which IoC bindings the provider provides.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'Stylemix\Settings\SettingsManager',
            'Stylemix\Settings\SettingStore',
            'setting'
        );
    }
}
