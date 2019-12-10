<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DuskServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        Route::get('/_dusk/login/{userId}/{guard?}', [
            'middleware' => 'web',
            'uses' => 'Laravel\Dusk\Http\Controllers\UserController@login',
        ]);

        Route::get('/_dusk/logout/{guard?}', [
            'middleware' => 'web',
            'uses' => 'Laravel\Dusk\Http\Controllers\UserController@logout',
        ]);

        Route::get('/_dusk/user/{guard?}', [
            'middleware' => 'web',
            'uses' => 'Laravel\Dusk\Http\Controllers\UserController@user',
        ]);

        $this->app->bind(
            'App\ApiInterface',
            'App\TestApi'
        );

        Route::get('/video/{id}', [
           'middleware' => 'web',
           'uses' => 'App\Http\Controllers\VideoController@view'
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     * @throws \Exception
     */
    public function register()
    {
        if ($this->app->environment('production')) {
            throw new Exception('It is unsafe to run Dusk in production.');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Laravel\Dusk\Console\InstallCommand::class,
                \Laravel\Dusk\Console\DuskCommand::class,
                \Laravel\Dusk\Console\DuskFailsCommand::class,
                \Laravel\Dusk\Console\MakeCommand::class,
                \Laravel\Dusk\Console\PageCommand::class,
                \Laravel\Dusk\Console\ComponentCommand::class,
                \Laravel\Dusk\Console\ChromeDriverCommand::class,
            ]);
        }
    }
}
