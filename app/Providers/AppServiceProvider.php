<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\adminModels\UserAdmin;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
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
        view()->composer('*',function($view) {
            $view->with('extra_button', false);

            $usuario = @UserAdmin::where('id', @Auth::id())->get()[0];
            Config::set('nombre_usuario', strtoupper(@$usuario->name));

        });
    }
}
