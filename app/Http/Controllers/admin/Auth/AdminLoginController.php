<?php

namespace App\Http\Controllers\admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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
      dd('1');
      $this->validate($request,[
        'usersys'=>'required',
        'password'=>'required',
      ]);
      $credentials=['usersys'=>$request->usersys,
                    'password'=>$request->password];

      if(Auth::guard('admin')->attempt($credentials,$request->remember)){

        $usuario = UserAdmin::where('id', Auth::guard('admin')->id())->get()[0];
        session('global_id_mesero', Auth::guard('admin')->id());

        if ($usuario->roleUS == 1) {
          $ruta = '/admin/users';
        } elseif ($usuario->roleUS == 2) {
          $ruta = route('admin.reservas.lista_invitados');
        } elseif ($usuario->roleUS == 3) {
          $ruta = route('admin.reservas.mesas');
        } elseif ($usuario->roleUS == 4) {
          $ruta = route('admin.mesero.balance');
        } elseif ($usuario->roleUS == 5) {
          $ruta = route('admin.mesero.pedidos');
        } elseif ($usuario->roleUS == 8) {
          $ruta = '/admin/acreditaciones';
        } elseif ($usuario->roleUS == 9) {
          $ruta = '/admin/control_de_ingreso';
        } elseif ($usuario->roleUS == 10) {
          $ruta = route('admin.bodega.inventario');
        }

        Session::put('global_id_mesero', 0);

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
      $estl = route('admin.reservas.lista_eventos');
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
