<?php

namespace App\Http\Controllers\admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\adminModels\UserAdmin;
use Auth;
use Route;
class AdminLoginController extends Controller
{
    public function __construct(){
      $this->middleware("guest:admin",["except"=>['logout','userlogout']]);
    }
    public function showLoginForm(){
      // echo var_dump(route('home'));
      return view('admin.Auth.login');
    }
    public function login(Request $request){
      $this->validate($request,[
        'usersys'=>'required',
        'password'=>'required',
      ]);
      $credentials=['usersys'=>$request->usersys,
                    'password'=>$request->password];

      if(Auth::guard('admin')->attempt($credentials,$request->remember)){

        $usuario = UserAdmin::where('id', Auth::guard('admin')->id())->get()[0];
        if ($usuario->roleUS == 2) {
          $ruta = route('admin.encargado.asignacion');
        } elseif ($usuario->roleUS == 3) {
          $ruta = route('admin.bodega.inventario');
        } elseif ($usuario->roleUS == 4 || $usuario->roleUS == 6) {
          $ruta = route('admin.cobrador.pedidos_por_cobrar');
        } elseif ($usuario->roleUS == 5) {
          $ruta = route('admin.mesero.pedidos');
        } elseif ($usuario->roleUS == 7) {
          $ruta = route('admin.gerencia.resumen');
        } elseif ($usuario->roleUS == 8) {
          $ruta = route('admin.encargado.cierre_total');
        } else {
          $ruta = route('admin.productos.index');
        }

        return redirect($ruta);
      }
      $errors = new MessageBag(['password' => ['Usuario o contraseña invalidos.']]);

      return redirect()->back()->withInput($request->only('usersys','remember'))->withErrors('test');
    }
    public function username()
    {
        return 'usersys';
    }
    public function logout(){
      Auth::guard('admin')->logout();
      $estl = route('admin.dashboard');
      return redirect($estl);
    }

  // protected function authenticated(Request $request, $user)
  // {
  //   dd($request);
  // if ( $user->isAdmin() ) {// do your magic here
  //     return redirect()->route('home');
  // }
  //
  //  return redirect('/');
  // }
}
