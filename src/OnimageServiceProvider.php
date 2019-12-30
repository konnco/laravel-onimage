<?php

namespace Konnco\Onimage;

use Illuminate\Support\ServiceProvider;

class OnimageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishMigrations();
//        $this->publishModels();
        $this->publishConfig();
    }

    public function publishMigrations()
    {
        $this->publishes([__DIR__.'/migrations' => database_path('migrations')], 'onimage');
    }

    public function publishModels()
    {
        $this->publishes([__DIR__.'/models' => app_path()], 'onimage');
    }

    public function publishConfig()
    {
        $this->publishes([__DIR__.'/config/onimage.php' => config_path('onimage.php')], 'onimage');
        $this->mergeConfigFrom(__DIR__.'/config/onimage.php', 'onimage');
    }
}
