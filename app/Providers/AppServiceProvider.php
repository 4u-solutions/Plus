<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\adminModels\UserAdmin;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

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
        if(env('APP_ENV') == 'production'){
            URL::forceScheme('https');
        }

        view()->composer('*',function($view) {
            $view->with('extra_button', false);

            $str = "select * from admin_eventos where estado and fecha >= '" . date('Y-m-d') . "' order by fecha limit 1;";
            $evento = DB::select($str)[0];  
            $id_evento = $evento->id;
            Config::set('app.fecha_evento', $id_evento);

            $usuario = @UserAdmin::where('id', @Auth::id())->get()[0];
            Config::set('nombre_usuario', strtoupper(@$usuario->name));
            
            if (session('global_id_mesero')) {
                $usuario = @UserAdmin::where('id', session('global_id_mesero'))->get()[0];
                Config::set('nombre_mesero', strtoupper(@$usuario->name));
            } else {
                Config::set('nombre_mesero', '');
            }

        });
    }
}
