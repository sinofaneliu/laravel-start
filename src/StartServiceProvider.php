<?php
/*
 * @Description: 
 * @Author: sinofaneliu@qq.com
 * @Date: 2020-07-27 10:00:37
 * @LastEditTime: 2020-07-27 15:45:41
 * @LastEditors: sinofaneliu@qq.com
 */ 

namespace Sinofaneliu\LaravelStart;

use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use Sinofaneliu\LaravelStart\Commands\CreateModel;
use Sinofaneliu\LaravelStart\Commands\InstallUsefulPackages;

class StartServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // $this->loadMigrationsFrom(__DIR__.'/path/to/migrations');

        // $this->loadFactoriesFrom(__DIR__.'/path/to/factories');
        
        // $this->loadTranslationsFrom(__DIR__.'/path/to/translations', 'courier');
        // $this->publishes([
        //     __DIR__.'/path/to/translations' => resource_path('lang/vendor/courier'),
        // ]);

        // $this->loadViewsFrom(__DIR__.'/path/to/views', 'courier');
        // $this->publishes([
        //     __DIR__.'/path/to/views' => resource_path('views/vendor/courier'),
        // ]);

        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         FooCommand::class,
        //         BarCommand::class,
        //     ]);
        // }

        // $this->publishes([
        //     __DIR__.'/path/to/assets' => public_path('vendor/courier'),
        // ], 'public');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateModel::class,
                InstallUsefulPackages::class,
            ]);
        }

        Passport::routes();
    }

    public function register()
    {
        // $this->mergeConfigFrom(
        //     __DIR__.'/path/to/config/courier.php', 'courier'
        // );
    }
}