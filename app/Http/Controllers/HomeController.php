<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usuario = UserAdmin::where('id', Auth::id())->get()[0];
        if ($usuario->roleUS == 9) {
          $ruta = route('admin.reservas.lista_eventos');
        } elseif ($usuario->roleUS == 10) {
          $ruta = route('admin.reservas.mesas');
        } else {
          $ruta = route('admin.reservas.lista_eventos');
        }

        return view($ruta);
    }
}
