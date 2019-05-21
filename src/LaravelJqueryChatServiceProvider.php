<?php

namespace Dieegogd\LaravelJqueryChat;

use Illuminate\Support\ServiceProvider;

class LaravelJqueryChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Registra las rutas y macros
        $this->loadRoutesFrom(__DIR__.'/routes/laravel-jquery-chat.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-jquery-chat');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([                                        
            __DIR__.'/resources/views' => resource_path('views'), 
        ], 'public');                                             
        $this->publishes([
            __DIR__.'/resources/js' => resource_path('js'),
        ], 'public');
        $this->publishes([
            __DIR__.'/resources/sass' => resource_path('sass'),
        ], 'public');
    }
}
